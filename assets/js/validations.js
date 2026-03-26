document.addEventListener("DOMContentLoaded", function(){

    //Se revisa la URL
    const urlParams = new URLSearchParams(window.location.search);

    console.log("Parámetros en URL:", urlParams.toString());

        //Se verifica si existe el parámetro
        if(urlParams.get('success') === '1'){

            Swal.fire({

                title: '¡Registro Exitoso!',
                text: 'Ya puedes iniciar sesión',
                icon: 'success',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#198754',
                timer: 5000, //Se cierra solo en 5 segundos si el usuario no hace nada
                timerProgressBar: true

            });

            //Se limpia la URL sin recargar la página
            const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
            window.history.replaceState({path: cleanUrl}, '', cleanUrl);

        }

});