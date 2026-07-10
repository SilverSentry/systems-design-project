document.addEventListener("DOMContentLoaded", function () {

    const editClientForm = document.getElementById("editClientForm");

    //Constante para seleccionar todos los input
    const inputs = editClientForm ? editClientForm.querySelectorAll('input') : [];

    inputs.forEach(input => {
        input.addEventListener("input", function () {
            //Apenas se empiece a escribir, se quita la clase de error de Bootstrap
            if (this.classList.contains("is-invalid")) {
                this.classList.remove("is-invalid");
            }
        });
    });

    if (editClientForm) {
        editClientForm.addEventListener("submit", async (e) => {
            e.preventDefault();

            const submitBtn = document.getElementById("submitBtn");
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...`;

            const formData = new FormData(e.target);
            const urlAction = e.target.getAttribute('action');
            const allInputs = e.target.querySelectorAll('.form-control');

            //1. Limpiar todos los bordes rojos previos
            if(typeof cleanAllInputs === 'function') cleanAllInputs('.form-control', 'is-invalid');

            try {
                const response = await fetch(urlAction, {
                    method: "POST",
                    body: formData
                });

                if (!response.ok) throw new Error("Error en la respuesta del servidor");

                const data = await response.json();

                if (data.status === 'success') {
                    if(typeof cleanAllInputs === 'function') cleanAllInputs('.form-control', 'is-invalid');
                    submitBtn.innerText = "¡Cliente actualizado!";

                    if(typeof showToast === 'function') showToast('success', data.message);
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1000);

                } else {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = "<i class='bi bi-person-check'></i> Actualizar cliente";

                    if(typeof showToast === 'function') showToast('error', data.message);

                    if (data.field === 'all') {
                        allInputs.forEach(input => input.classList.add("is-invalid"));
                    } else if (data.field) {
                        const input = document.getElementById(data.field);
                        if (input) input.classList.add("is-invalid");
                    }
                }

            } catch (error) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = "<i class='bi bi-person-check'></i> Actualizar cliente";
                console.error("Error capturado: ", error);
                if(typeof showToast === 'function') showToast('error', "Error de procesamiento en el servidor");
            }
        });
    }
});
