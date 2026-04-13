document.addEventListener("DOMContentLoaded", function(){
    
    //Se obtienen las id del formulario
    const formRegister = document.getElementById("formRegister");
    const errorContainer = document.getElementById("errorContainer");

    //Se seleccionan todos los inputs
    const inputs = formRegister.querySelectorAll('input');

        inputs.forEach(input => {
            input.addEventListener("input", function() {

                //Apenas se empiece a escribir, se quita la clase de error de Bootstrap
                if (this.classList.contains("is-invalid")) {

                    this.classList.remove("is-invalid");

                }
                
            });
        });
    
    //Captura de errores para el registro
    if(formRegister){

        formRegister.addEventListener("submit", async(e) =>{

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

                    //Manejo de mensaje de registro exitoso
                    if(data.status === 'success'){

                        //Se redirige
                        window.location.href = data.redirect;

                    } else{

                        //Si hay un error, se cambia el contenido del div dinámicamente
                        errorContainer.textContent = data.message;
                        errorContainer.classList.remove("invisible");
                        errorContainer.classList.add("visible");

                        //Se limpia cualquier borde rojo previo (para no acumular errores)
                        const allInputs = e.target.querySelectorAll('.form-control');
                        allInputs.forEach(i => i.classList.remove("is-invalid"));

                            //Caso para los campos vacíos
                            if(data.field === 'all') {

                                allInputs.forEach(input => { input.classList.add("is-invalid"); });

                            //Se marca ambos campos de contraseña
                            } else if(data.field === 'passwords'){

                                const p1 = document.getElementById("password");
                                const p2 = document.getElementById("passwordConfirm");

                                if(p1) p1.classList.add("is-invalid");
                                if(p2) p2.classList.add("is-invalid");

                            //Se marca solo el campo que envió el servidor
                            } else if(data.field){

                                const uniqueInput = document.getElementById(data.field);

                                if(uniqueInput) uniqueInput.classList.add("is-invalid");

                                uniqueInput.focus()

                            }
                            
                    }

                } catch(error) {
                    console.error("Error en la petición:", error);
                    errorContainer.textContent = "Ocurrió un error inesperado";
                    errorContainer.classList.remove("d-none");
                }
        });

    }

});