document.addEventListener('DOMContentLoaded', function () {
    const openServicesBtn = document.getElementById('openServicesBtn');
    const confirmServicesBtn = document.getElementById('confirmServicesBtn');
    const serviceCheckboxes = document.querySelectorAll('.service-checkbox');
    const serviceIdsInput = document.getElementById('serviceIdsInput');
    const scheduleAppointmentForm = document.getElementById('scheduleAppointmentForm');

    if (!openServicesBtn || !confirmServicesBtn || !serviceIdsInput || !scheduleAppointmentForm) {
        return;
    }

    const servicesModal = new bootstrap.Modal(document.getElementById('servicesModal'));

    openServicesBtn.addEventListener('click', function () {
        servicesModal.show();
    });

    confirmServicesBtn.addEventListener('click', function () {
        const selectedIds = Array.from(serviceCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);

        if (selectedIds.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Selecciona al menos un servicio',
                text: 'Debes elegir al menos un servicio para continuar con el agendado.',
                confirmButtonText: 'Entendido'
            });
            return;
        }

        serviceIdsInput.value = selectedIds.join(',');
        servicesModal.hide();
        scheduleAppointmentForm.submit();
    });
});
