document.addEventListener("DOMContentLoaded", function(){

    //Se revisa la URL
    const urlParams = new URLSearchParams(window.location.search);

    console.log("Parámetros en URL:", urlParams.toString());

        //Manejo del mensaje de error en el login
        if(urlParams.get('error') === 'auth_failed'){

            //
            const loginError = document.getElementById("incorrectCredentials");

            //
            if(loginError){
                
                //
                loginError.classList.remove("d-none");

            }

            //Se limpia la URL para que el mensaje desaparezca si se refresca la página
            const cleanUrl = window.location.origin + window.location.pathname + "?p=login";
            window.history.replaceState({}, document.title, cleanUrl);

        }

        //Manejo de mensaje de registro exitoso
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