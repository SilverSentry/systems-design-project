/**
 * Arhivo JavaScript principal de la aplicación. Aquí se agregan scripts personalizados o importar otros archivos JavaScript según sea necesario
 * Este archivo se carga en todas las páginas
 */


//Lanza un Toast rápido en la parte superior (Notificaciones efímeras)
function showToast(icon, title) {
    Swal.mixin({
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

function showToastError(title) {
    showToast('error', title);
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

function cleanAllInputs(querySelector, classToRemove) {
    const allInputs = document.querySelectorAll(querySelector);
    allInputs.forEach(input => input.classList.remove(classToRemove));
}