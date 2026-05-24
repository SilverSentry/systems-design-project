document.addEventListener("DOMContentLoaded", function(){
    
    //Se obtienen las id del formulario
    const formRegister = document.getElementById("formRegister");
    const errorContainer = document.getElementById("errorContainer");

    //Función para manejar el toggle de mostrar/ocultar contraseña
    const bindPasswordToggle = (toggleId, inputId) => {

        const toggleButton = document.getElementById(toggleId);
        const input = document.getElementById(inputId);

        //Si no se encuentran los elementos, se sale de la función para evitar errores
        if (!toggleButton || !input) return;

        //Se agrega el evento click al icono del ojo para mostrar/ocultar la contraseña
        toggleButton.addEventListener("click", function() {

            const type = input.getAttribute("type") === "password" ? "text" : "password";
            input.setAttribute("type", type);
            const icon = this.querySelector("i");
            icon.classList.toggle("bi-eye");
            icon.classList.toggle("bi-eye-slash");

        });
    };

    //Se vinculan los toggles de mostrar/ocultar contraseña para ambos campos
    bindPasswordToggle("togglePassword", "password");
    bindPasswordToggle("togglePasswordConfirm", "passwordConfirm");

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
                    submitBtn.innerHTML = "<i class='bi bi-person-plus'></i> Registrar";

                    //Mostrar un mensaje
                    Swal.mixin({
                        toast: true,
                        position: "top",                      
                        showConfirmButton: false,
                        timer: 2000,

                    }).fire({
                        icon: "error",
                        title: data.message,
                        customClass: { //Clase para agregar estilos personalizados a la alerta
                            popup: 'custom-swal-rect' //Nombre de la clase personalizada
                        },
                    });

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

                        }
                            
                }                  

            })
            .catch (error => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = "<i class='bi bi-person-plus'></i> Registrar";
                console.error("Error en la petición:", error);
                Swal.mixin({
                        toast: true,
                        position: "top",                      
                        showConfirmButton: false,
                        timer: 2000

                    }).fire({
                        icon: "error",
                        title: "Error de conexión con el servidor",
                        customClass: { //Clase para agregar estilos personalizados a la alerta
                            popup: 'custom-swal-rect' //Nombre de la clase personalizada
                        },
                    });
            });

        });

    }

});