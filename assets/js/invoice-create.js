document.addEventListener("DOMContentLoaded", function () {
    const appointmentSelect = document.getElementById("appointment_id");
    const clientInfoCard = document.getElementById("client-info-card");
    const servicesCard = document.getElementById("services-card");
    const submitBtn = document.getElementById("submitBtn");
    
    // Elementos de detalles del cliente
    const clientFullname = document.getElementById("client-fullname");
    const clientDni = document.getElementById("client-dni");
    const clientPhone = document.getElementById("client-phone");

    // Cuerpo de la lista de servicios
    const servicesList = document.getElementById("services-list");

    // Elementos del resumen
    const summarySubtotal = document.getElementById("summary-subtotal");
    const summaryIva = document.getElementById("summary-iva");
    const summaryTotalUsd = document.getElementById("summary-total-usd");
    const summaryTotalVes = document.getElementById("summary-total-ves");
    
    // Tasa BCV
    const bcvRateHidden = document.getElementById("bcv_rate_hidden");
    const bcvRate = bcvRateHidden ? parseFloat(bcvRateHidden.value) : 1.0;

    // Limpiar inputs del formulario al interactuar
    const createInvoiceForm = document.getElementById("createInvoiceForm");
    const selectInputs = createInvoiceForm ? createInvoiceForm.querySelectorAll("select") : [];

    selectInputs.forEach(select => {
        select.addEventListener("change", function () {
            if (this.classList.contains("is-invalid")) {
                this.classList.remove("is-invalid");
            }
        });
    });

    // Ayudante para formatear decimales de moneda en formato español
    function formatCurrency(value, prefix = '$') {
        return prefix + ' ' + value.toFixed(2).replace('.', ',').replace(/\d(?=(\d{3})+,)/g, '$&.');
    }

    // Obtener detalles de la cita cuando se selecciona una nueva
    appointmentSelect.addEventListener("change", async function () {
        const appointmentId = this.value;
        if (!appointmentId) {
            resetInvoicingForm();
            return;
        }

        // Mostrar estado de carga
        appointmentSelect.disabled = true;
        
        try {
            const response = await fetch(window.AppBasePath + 'api/appointments/details?id=' + appointmentId);
            
            if (!response.ok) {
                throw new Error("No se pudo cargar la información de la cita");
            }

            const res = await response.json();

            if (res.status === 'success' && res.data) {
                populateAppointmentDetails(res.data);
            } else {
                showToast('error', res.message || 'Error cargando datos de la cita');
                resetInvoicingForm();
            }

        } catch (error) {
            console.error('Error fetching appointment details:', error);
            showToast('error', 'Error al consultar la cita en el servidor');
            resetInvoicingForm();
        } finally {
            appointmentSelect.disabled = false;
        }
    });

    // Rellenar la UI con los datos obtenidos
    function populateAppointmentDetails(data) {
        // 1. Asignar detalles del cliente
        clientFullname.innerText = (data.client_name + ' ' + data.client_surname).toUpperCase();
        clientDni.innerText = data.client_dni ? data.client_dni : 'S/D';
        clientPhone.innerText = data.client_phone ? data.client_phone : 'S/D';

        // 2. Rellenar la lista de servicios
        servicesList.innerHTML = '';
        let subtotal = 0.0;

        const services = Array.isArray(data.services) ? data.services : [];
        if (services.length === 0) {
            servicesList.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center text-muted py-3">No hay servicios registrados en esta cita</td>
                </tr>
            `;
        } else {
            services.forEach(service => {
                const price = parseFloat(service.precio || 0.0);
                subtotal += price;

                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td class="ps-4">
                        <span class="fw-semibold text-dark">${service.nombre}</span>
                    </td>
                    <td>${formatCurrency(price)}</td>
                    <td>1</td>
                    <td class="text-end pe-4 fw-semibold text-dark">${formatCurrency(price)}</td>
                `;
                servicesList.appendChild(tr);
            });
        }

        // 3. Calcular y mostrar totales
        const iva = subtotal * 0.16; // 16% IVA
        const totalUsd = subtotal + iva;
        const totalVes = totalUsd * bcvRate;

        summarySubtotal.innerText = formatCurrency(subtotal);
        summaryIva.innerText = formatCurrency(iva);
        summaryTotalUsd.innerText = formatCurrency(totalUsd);
        summaryTotalVes.innerText = formatCurrency(totalVes, 'Bs.');

        // 4. Mostrar las tarjetas
        clientInfoCard.classList.remove("d-none");
        servicesCard.classList.remove("d-none");
        
        // Activar el botón de registro si hay servicios
        submitBtn.disabled = services.length === 0;
    }

    // Restablecer el formulario cuando no se selecciona cita
    function resetInvoicingForm() {
        clientFullname.innerText = "-";
        clientDni.innerText = "-";
        clientPhone.innerText = "-";
        servicesList.innerHTML = '';
        summarySubtotal.innerText = "$0.00";
        summaryIva.innerText = "$0.00";
        summaryTotalUsd.innerText = "$0.00";
        summaryTotalVes.innerText = "Bs. 0,00";

        clientInfoCard.classList.add("d-none");
        servicesCard.classList.add("d-none");
        submitBtn.disabled = true;
    }

    // Manejar envío del formulario por AJAX
    if (createInvoiceForm) {
        createInvoiceForm.addEventListener("submit", async function (e) {
            e.preventDefault();

            // Deshabilitar botón y mostrar spinner
            submitBtn.disabled = true;
            const originalBtnHtml = submitBtn.innerHTML;
            submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...`;

            const formData = new FormData(this);
            const urlAction = this.getAttribute("action");

            cleanAllInputs('.form-control', 'is-invalid');

            try {
                const response = await fetch(urlAction, {
                    method: "POST",
                    body: formData
                });

                if (!response.ok) {
                    throw new Error("Error de red en el servidor");
                }

                const data = await response.json();

                if (data.status === 'success') {
                    showAlert('success', 'Factura Registrada', data.message, '#28a745')
                        .then(() => {
                            window.location.href = data.redirect;
                        });
                } else {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnHtml;
                    
                    showToast('error', data.message);

                    if (data.field === 'all') {
                        addAllInput('.form-control', 'is-invalid');
                    } else if (data.field) {
                        const errInput = document.getElementById(data.field);
                        if (errInput) errInput.classList.add('is-invalid');
                    }
                }

            } catch (error) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
                console.error('Submit error:', error);
                showToast('error', 'Error de comunicación con el servidor');
            }
        });
    }
});
