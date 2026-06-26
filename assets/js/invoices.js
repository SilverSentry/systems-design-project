$(document).ready(function () {
    // Initialize DataTable for invoices list
    $('#tabla-facturas').DataTable({
        language: {
            url: '/StudioOrdoStetic/assets/DataTables/es-ES.json'
        },
        order: [[2, 'desc']] // Order by date descending by default
    });

    // Handle cancellation / voiding of invoices
    $(document).on('click', '.btn-cancel-invoice', function () {
        const invoiceId = $(this).data('id');
        const invoiceNumber = $(this).data('number');

        Swal.fire({
            title: '¿Está seguro de anular la factura ' + invoiceNumber + '?',
            text: 'Esta acción es irreversible y cambiará el estado de la factura a anulada.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, anular',
            cancelButtonText: 'Cancelar',
            customClass: { popup: 'custom-swal-rect' }
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('id', invoiceId);

                fetch(window.AppBasePath + 'invoices/cancel', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) throw new Error('Error en el servidor');
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        showAlert('success', 'Factura Anulada', data.message, '#dc3545')
                            .then(() => {
                                window.location.reload();
                            });
                    } else {
                        showToast('error', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error canceling invoice:', error);
                    showToast('error', 'Error al anular la factura en el servidor');
                });
            }
        });
    });
});
