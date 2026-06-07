document.addEventListener("DOMContentLoaded", function(){

    //Toggle para mostrar/ocultar contraseña
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    //Se agrega el evento click al icono del ojo para mostrar/ocultar la contraseña
    if(togglePassword && passwordInput) {

        //Se agrega el evento click al icono del ojo para mostrar/ocultar la contraseña
        togglePassword.addEventListener('click', function() {

            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            const icon = this.querySelector('i');
            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');

        });
    }

    //Constante para revisar la URL
    const urlParams = new URLSearchParams(window.location.search);

    //Se obtiene las id del formulario
    const formLogin = document.getElementById("formLogin");

    //Contenedor para mostrar errores generales (no relacionados con un campo específico)
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

            const submitBtn = document.getElementById("submitBtn");

            submitBtn.disabled = true;
            submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Validando...`;

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

                    submitBtn.innerText = "¡Acceso concedido!";
                    window.location.href = data.redirect;

                //En otro caso, se muestran los errores correspondientes
                } else{

                    submitBtn.disabled = false;
                    submitBtn.innerHTML = "<i class='bi bi-box-arrow-in-right'></i> Ingresar";

                    //Usamos el Toast genérico de app.js
                    showToast("error", data.message);

                    //Se limpia cualquier borde rojo previo (para no acumular errores)
                    const allInputs = e.target.querySelectorAll('.form-control');
                    allInputs.forEach(i => i.classList.remove("is-invalid"));

                    //Manejo de error de formato no válido del correo
                    if(data.field === 'email') {

                        const emailError = document.getElementById(data.field);

                        if(emailError) emailError.classList.add("is-invalid");

                    //Manejo de otros mensajes de error
                    } else{
                            
                        const allInputs = e.target.querySelectorAll('.form-control');
                        allInputs.forEach(i => i.classList.remove("is-invalid"));

                            if(data.field === 'all'){

                                allInputs.forEach(input => { input.classList.add("is-invalid"); });

                            }
                    }

                }
            })
             
            .catch (error => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = "<i class='bi bi-box-arrow-in-right'></i> Ingresar";
                console.error("Error en la petición: ", error);
                showToast("error", "Error de conexión con el servidor");
            });
        });
    }

    //Manejo de mensaje de registro exitoso
    if(urlParams.get('success') === '1'){
        showAlert('success', '¡Registro Exitoso!', 'Ya puedes iniciar sesión', '#28a745')
        .then(() => cleanUrlParams());
    }

    //Manejo de mensaje de error por acceso sin sesión (cuando se redirige al login)
    //Buscamos el elemento contenedor que guardó el estado de PHP
    const authStatus = document.getElementById('auth-status');

    if (authStatus) {
        //Leemos el atributo data-error y verificamos si es estrictamente 'true'
        const hasAuthError = authStatus.getAttribute('data-error') === 'true';

        if (hasAuthError) {
            showAlert('warning', 'Acceso denegado', 'Debes iniciar sesión para poder acceder a esta sección.', '#eb1010')
            .then(() => cleanUrlParams());
        }
    }

});