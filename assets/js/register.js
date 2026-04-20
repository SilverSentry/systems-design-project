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

            const submitBtn = document.getElementById("submitBtn");

            submitBtn.disabled = true;
            submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Validando datos...`;

            //Se recogen los datos del formulario
            const formData = new FormData(e.target);
            const urlAction = e.target.getAttribute('action');

            fetch(urlAction, {
                method: "POST",
                body: formData
            })

            .then(response => response.json())
            .then(data => {

                //Si todo está bien, se redirige
                if(data.status === 'success'){

                    submitBtn.innerText = "¡Éxito! Redirigiendo...";
                    window.location.href = data.redirect;

                //En caso contrario, se muestra los respectivos mensajes de error
                } else{

                    submitBtn.disabled = false;
                    submitBtn.innerText = 'Registrar';

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

            })
            .catch (error => {
                submitBtn.disabled = false;
                submitBtn.innerText = "Registrar";
                console.error("Error en la petición:", error);
                errorContainer.textContent = "Error de conexión con el servidor";
                errorContainer.classList.remove("invisible");
                errorContainer.classList.add("visible");
            });

        });

    }

});