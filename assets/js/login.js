document.addEventListener("DOMContentLoaded", function(){

    //Constante para revisar la URL
    const urlParams = new URLSearchParams(window.location.search);

    //Se obtiene las id del formulario
    const formLogin = document.getElementById("formLogin");
    const errorContainer = document.getElementById("errorContainer");

    //Constante para seleccionar todos los input
    const inputs = formLogin.querySelectorAll('input');

        inputs.forEach(input => {
            input.addEventListener("input", function() {

                //Apenas se empiece a escribir, se quita la clase de error de Bootstrap
                if (this.classList.contains("is-invalid")) {

                    this.classList.remove("is-invalid");

                }
                
            });
        });

    //Captura de errores para el login
    if(formLogin){

        formLogin.addEventListener("submit", async(e) =>{

            e.preventDefault(); //Evita que se recargue la página

            //Se recogen los datos del formulario
            const formData = new FormData(e.target);

                try{

                    //Se envían los datos al controlador
                    const response = await fetch(e.target.getAttribute('action'), {
                        method: 'POST',
                        body: formData
                    });

                    //Se convierte la respuesta a un objeto JS
                    const data = await response.json();

                    if (data.status === 'success') {

                        //Si todo está bien, se redirige
                        window.location.href = data.redirect;

                    } else{

                        //Manejo de error de formato no válido del correo
                        if(data.field === 'email') {

                            //Se limpia cualquier borde rojo previo (para no acumular errores)
                            const allInputs = e.target.querySelectorAll('.form-control');
                            allInputs.forEach(i => i.classList.remove("is-invalid"));

                            const uniqueInput = document.getElementById(data.field);

                            uniqueInput.classList.add("is-invalid");

                            errorContainer.textContent = data.message;
                            errorContainer.classList.remove("invisible");
                            errorContainer.classList.add("visible");

                        } else{

                            //Se muestran los demás errores
                            errorContainer.textContent = data.message;
                            errorContainer.classList.remove("invisible");
                            errorContainer.classList.add("visible");
                            
                            const allInputs = e.target.querySelectorAll('.form-control');
                            allInputs.forEach(i => i.classList.remove("is-invalid"));

                            if(data.status === 'error'){

                                allInputs.forEach(input => { input.classList.add("is-invalid"); });

                            }
                        }
                    }

            } catch (error) {
                console.error("Error en la petición:", error);
                errorContainer.textContent = "Error de conexión con el servidor";
                errorContainer.classList.remove("invisible");
                errorContainer.classList.add("visible");
            }
        });
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