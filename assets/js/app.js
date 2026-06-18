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

document.addEventListener('DOMContentLoaded', function () {
    initSessionTimeout(15 * 60);
});