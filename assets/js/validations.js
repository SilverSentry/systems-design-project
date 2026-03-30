document.addEventListener("DOMContentLoaded", function(){

    //Se revisa la URL
    const urlParams = new URLSearchParams(window.location.search);

    //Se obtiene el id del error
    const errorContainer = document.getElementById("errorContainer");

        //Manejo de mensajes de error tanto en el login como en el registro
        if(urlParams.has('error')){

            //Se obtiene el error
            const errorType = urlParams.get('error');

            //Se muestra el mensaje
            errorContainer.classList.remove("d-none");
            
            //Se hace uso de switch para cada caso
            switch(errorType){

                //Caso para campos vacíos
                case 'empty_fields':
                    errorContainer.textContent = "Rellene todos los campos";
                    break;

                //Caso para credenciales incorrectas
                case 'auth_failed':
                    errorContainer.textContent = "Credenciales incorrectas";
                    break;

                //Caso para nombre y apellido no válidos
                case 'invalid_name':
                    errorContainer.textContent = "Nombre y apellido no válidos";
                    break;

                //Caso para email no válido
                case 'invalid_email':
                    errorContainer.textContent = "El formato del email no es válido";
                    break;

                //Caso para contraseña no válida
                case 'invalid_pass':
                    errorContainer.textContent = "Formato de contraseña no válido.";
                    break;

                //Mensaje prederteminado para cualquier otro error fuera de los anteriores
                default:
                    errorContainer.textContent = "Ocurrió un error inesperado";

            }

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