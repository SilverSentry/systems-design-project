document.addEventListener('DOMContentLoaded', function () {
    const openServicesBtn = document.getElementById('openServicesBtn');
    const confirmServicesBtn = document.getElementById('confirmServicesBtn');
    const serviceCheckboxes = document.querySelectorAll('.service-checkbox');
    const serviceIdsInput = document.getElementById('serviceIdsInput');
    const scheduleAppointmentForm = document.getElementById('scheduleAppointmentForm');
    const amountInput = document.getElementById('amount');
    const selectedServicesSummary = document.getElementById('selectedServicesSummary');

    if (!openServicesBtn || !confirmServicesBtn || !serviceIdsInput || !scheduleAppointmentForm || !amountInput || !selectedServicesSummary) {
        return;
    }

    const servicesModal = new bootstrap.Modal(document.getElementById('servicesModal'));

    function updateTotalAmount() {
        const selected = Array.from(serviceCheckboxes).filter(checkbox => checkbox.checked);
        const total = selected.reduce((sum, checkbox) => {
            const price = parseFloat(checkbox.dataset.price || '0');
            return sum + (isNaN(price) ? 0 : price);
        }, 0);

        amountInput.value = total.toFixed(2);
        selectedServicesSummary.textContent = selected.length > 0
            ? 'Servicios seleccionados: ' + selected.map(checkbox => checkbox.nextElementSibling?.textContent?.trim() || 'Servicio').join(', ')
            : 'Servicios seleccionados: ninguno';
    }

    serviceCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateTotalAmount);
    });

    openServicesBtn.addEventListener('click', function () {
        servicesModal.show();
    });

    confirmServicesBtn.addEventListener('click', function () {
        const selectedServices = Array.from(serviceCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => ({
                id: checkbox.value,
                name: checkbox.nextElementSibling?.textContent?.trim() || 'Servicio',
            }));

        if (selectedServices.length === 0) {
            showAlert('warning', 'Selecciona al menos un servicio', 'Debes elegir al menos un servicio para continuar con el agendado', '#ffc107');
            return;
        }

        serviceIdsInput.value = selectedServices.map(service => service.id).join(',');
        servicesModal.hide();
        updateTotalAmount();
    });

    const appointmentError = document.getElementById('appointment-error');
    if (appointmentError) {
        const errorMessage = appointmentError.getAttribute('data-message');
        const errorField = appointmentError.getAttribute('data-field');

        if (errorMessage) {
            showAlert('warning', 'Error al agendar la cita', errorMessage, '#ffc107');

            if (errorField === 'service_ids') {
                servicesModal.show();
            }
        }
    }

    updateTotalAmount();

    //Bloque para manejar los mensajes de error 
    if (scheduleAppointmentForm) {

        //Se usa async/await
        scheduleAppointmentForm.addEventListener("submit", async (e) => {
            e.preventDefault();

            const submitBtn = document.getElementById("submitBtn");
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...`;

            const formData = new FormData(e.target);
            const urlAction = e.target.getAttribute('action');

            const allInputs = e.target.querySelectorAll('.form-control');

            //1. Limpiar todos los bordes rojos previos al inicio de la validación
            cleanAllInputs('.form-control', 'is-invalid');

            try {
                const response = await fetch(urlAction, {
                    method: "POST",
                    body: formData
                });

                //Si el servidor responde un error fatal (ej: 500 o 404), saltamos al catch
                if (!response.ok) throw new Error("Error en la respuesta del servidor");

                const data = await response.json();

                //Si el registro es exitoso, mostramos un mensaje
                if (data.status === 'success') {

                    cleanAllInputs('.form-control', 'is-invalid');
                    submitBtn.innerText = "¡Cita agendada!";

                    showAlert('success', data.message, '¿Desea realizar una reservación?', '#2aeb10')
                        .then(() => {

                            //Redirección
                            window.location.href = data.redirect;
                        });

                    //En caso de error, se muestra los mensajes de error correspondientes
                } else {

                    //Restablecer botón en caso de error de validación
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = "<i class='bi bi-calendar-check'></i> Agendar";

                    //Mostrar mensaje de error enviado desde PHP
                    showToast("error", data.message);

                    //2. Pintar bordes rojos según la respuesta
                    if (data.field === 'all') {
                        allInputs.forEach(input => input.classList.add("is-invalid"));

                    } else if (data.field) {
                        const input = document.getElementById(data.field);

                        if (input) input.classList.add("is-invalid");
                    }
                }

            } catch (error) {

                //Manejo de caídas de conexión o errores sintácticos de PHP (HTML en lugar de JSON)
                submitBtn.disabled = false;
                submitBtn.innerHTML = "<i class='bi bi-person-plus'></i> Registrar cliente";
                console.error("Error capturado: ", error);

                showToast('error', "Error de procesamiento en el servidor");
            }
        });
    }

});
