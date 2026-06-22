/**
 * Arhivo JavaScript principal de la aplicación. Aquí se agregan scripts personalizados o importar otros archivos JavaScript según sea necesario
 * Este archivo se carga en todas las páginas
 */


//Lanza un Toast rápido en la parte superior (Notificaciones efímeras)
function showToast(icon, title) {
    return Swal.mixin({
        toast: true,
        position: "top",                      
        showConfirmButton: false,
        timer: 2000
    }).fire({
        icon: icon,
        title: title,
        customClass: { popup: 'custom-swal-rect' }
    });
}

//Lanza un Modal de alerta estándar (Requiere interacción)
function showAlert(icon, title, text, confirmColor = '#28a745') {
    return Swal.fire({
        icon: icon,
        title: title,
        text: text,
        confirmButtonText: 'Aceptar',
        confirmButtonColor: confirmColor,
        customClass: { popup: 'custom-swal-rect' }
    });
}

//Lanza un Modal de alerta con temporizador (Se cierra solo después de un tiempo)
function showAlertWithTimer(icon, title, text, timer, confirmColor = '#28a745') {
    return Swal.fire({
        icon: icon,
        title: title,
        text: text,
        timer: timer,
        timerProgressBar: true,
        confirmButtonText: 'Aceptar',
        confirmButtonColor: confirmColor,
        customClass: { popup: 'custom-swal-rect' }
    });
}

//Limpia el parámetro de la URL de forma limpia sin recargar
function cleanUrlParams() {
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.pathname);
    }
}

//Obtiene el valor de un parámetro específico de la URL
function getQueryParam(name) {
    return new URLSearchParams(window.location.search).get(name);
}

//Redirige al usuario a la página de login con un mensaje de sesión expirada
function redirectToLoginWithExpiredMessage() {
    const loginUrl = window.AppBasePath + 'login?session_expired=1';
    window.location.href = loginUrl;
}

//Inicializa el temporizador de sesión para redirigir al login después de un período de inactividad
function initSessionTimeout(timeoutSeconds = 900) {
    if (window.isUserLoggedIn !== true) {
        return;
    }

    let lastInteraction = Date.now();

    const resetTimer = () => {
        lastInteraction = Date.now();
    };

    const events = ['mousemove', 'keydown', 'click', 'scroll', 'touchstart'];
    events.forEach(event => window.addEventListener(event, resetTimer));

    setInterval(() => {
        const secondsIdle = Math.floor((Date.now() - lastInteraction) / 1000);
        if (secondsIdle >= timeoutSeconds) {
            redirectToLoginWithExpiredMessage();
        }
    }, 1000);
}

//Función para agregar una clase a todos los inputs que coincidan con un selector
function addAllInput(querySelector, classToAdd) {
    const allInputs = document.querySelectorAll(querySelector);
    allInputs.forEach(input => input.classList.add(classToAdd));
}

//Función para limpiar clases de error o validación de todos los inputs que coincidan con un selector
function cleanAllInputs(querySelector, classToRemove) {
    const allInputs = document.querySelectorAll(querySelector);
    allInputs.forEach(input => input.classList.remove(classToRemove));
}

//Función para actualizar la tasa, tanto en la sección de escritorio como en la móvil
function updateTasaDisplay(data) {
    const desktopValor = document.getElementById('tasa-valor');
    const desktopFecha = document.getElementById('tasa-fecha');
    const mobileValor = document.getElementById('tasa-valor-mobile');
    const mobileFecha = document.getElementById('tasa-fecha-mobile');

    if (desktopValor) {
        desktopValor.innerText = data.bcv.toFixed(2).replace('.', ',');
    }
    if (desktopFecha) {
        desktopFecha.innerText = `Ref: ${data.date}`;
    }
    if (mobileValor) {
        mobileValor.innerText = data.bcv.toFixed(2).replace('.', ',');
    }
    if (mobileFecha) {
        mobileFecha.innerText = `Ref: ${data.date}`;
    }
}

document.addEventListener('DOMContentLoaded', function () {
    initSessionTimeout(15 * 60);
});

/**
 * Módulo centralizado para interactuar con los endpoints de Studio Ordo Stetic
 */
const OrdoAPI = {
    //URL base del proyecto
    baseUrl: '/StudioOrdoStetic/api',

    /**
     * Helper genérico para peticiones HTTP
     */
    async request(endpoint, options = {}) {
        try {
            const response = await fetch(`${this.baseUrl}${endpoint}`, options);
            const responseBody = await response.json().catch(() => null);
            if (!response.ok) {
                const errorMessage = responseBody?.message || `Error HTTP: ${response.status}`;
                throw new Error(errorMessage);
            }
            return responseBody;
        } catch (error) {
            console.error(`Error en API (${endpoint}):`, error);
            throw error; //Re-lanzamos el error para que el componente visual lo maneje
        }
    },

    /**
     * Módulo del Dólar BCV
     */
    tasaBCV: {
        async actualizar() {
            return await OrdoAPI.request('/tasa-bcv/actualizar', { method: 'POST' });
        }
    },

    /**
     * Módulo del Catálogo SNOMED CT
     */
    snomed: {
        async buscar(termino) {
            return await OrdoAPI.request(`/snomed/buscar?q=${encodeURIComponent(termino)}`);
        }
    },

    /**
     * Módulo de Estadísticas para Gráficos
     */
    stats: {
        async getEmpleados() {
            return await OrdoAPI.request('/stats/empleados');
        }
    }
};

//Funcionalidad para actualizar la tasa del BCV
document.addEventListener('DOMContentLoaded', function () {
    const updateButtons = document.querySelectorAll('.btn-actualizar-tasa');
    const refreshIcons = document.querySelectorAll('.refresh-icon');

    if (updateButtons.length === 0) {
        return;
    }

    const setButtonsDisabled = (disabled) => {
        updateButtons.forEach(button => button.disabled = disabled);
    };

    const toggleIcons = (spinning) => {
        refreshIcons.forEach(icon => {
            if (spinning) {
                icon.classList.add('spin-animation');
            } else {
                icon.classList.remove('spin-animation');
            }
        });
    };

    const clickHandler = async () => {
        setButtonsDisabled(true);
        toggleIcons(true);

        try {
            const data = await OrdoAPI.tasaBCV.actualizar();
            updateTasaDisplay(data);

            if (data.status === 'success') {
                showAlert('success', data.message, `Dólar BCV: Bs. ${data.bcv}`, '#28a745');
            }
        } catch (error) {
            let message = 'No se pudo actualizar la tasa del BCV. Intente nuevamente más tarde.';

            if (error instanceof TypeError ||
                (error.message && error.message.toLowerCase().includes('failed to fetch')) ||
                (error.message && error.message.toLowerCase().includes('networkerror'))
            ) {
                message = 'No se pudo actualizar la tasa del BCV. Verifica tu conexión a internet e inténtalo de nuevo.';
            } else if (error.message) {
                message = error.message;
            }

            showAlert('error', 'Error', message, '#dc3545');
        } finally {
            setButtonsDisabled(false);
            toggleIcons(false);
        }
    };

    updateButtons.forEach(button => button.addEventListener('click', clickHandler));
});