$(document).ready(function () {
    //Inicializar DataTable para la tabla de citas
    $('#tabla-citas').DataTable({
        language: {
            url: '/StudioOrdoStetic/assets/DataTables/es-ES.json'
        },
    });

    //Inicializar DataTable para la tabla de servicios
    $('#tabla-servicios').DataTable({
        language: {
            url: '/StudioOrdoStetic/assets/DataTables/es-ES.json'
        },
    });

    const servicesModalEl = document.getElementById('appointmentServicesModal');
    if (servicesModalEl) {
        const servicesModal = new bootstrap.Modal(servicesModalEl);
        const servicesList = servicesModalEl.querySelector('#appointmentServicesList');

        document.querySelectorAll('.btn-view-services').forEach(function (button) {
            button.addEventListener('click', function () {
                const rawServices = button.dataset.services || '';
                const items = rawServices
                    .split(/\s*\|\s*|\s*,\s*/)
                    .map(function (service) {
                        return service.trim();
                    })
                    .filter(Boolean);

                if (items.length === 0) {
                    servicesList.innerHTML = '<p class="mb-0 text-muted">Sin servicios registrados.</p>';
                } else {
                    const listItems = items.map(function (service) {
                        return '<li class="list-group-item">' + service + '</li>';
                    }).join('');
                    servicesList.innerHTML = '<ul class="list-group">' + listItems + '</ul>';
                }

                servicesModal.show();
            });
        });
    }

    $(document).on('submit', '.form-cancel-appointment', function(e) {
        e.preventDefault();
        const form = this;
        
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Se cancelará esta cita y no podrá ser restaurada.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, cancelar',
            cancelButtonText: 'No, mantener',
            customClass: { popup: 'custom-swal-rect' }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});