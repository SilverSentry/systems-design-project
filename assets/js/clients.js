$(document).ready(function () {
    $('#tabla-clientes').DataTable({
        language: {
            url: '/StudioOrdoStetic/assets/DataTables/es-ES.json'
        }
    });

    $(document).on('submit', '.form-delete-client', function(e) {
        e.preventDefault();
        const form = this;
        
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Se desactivará este cliente y no será visible.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            customClass: { popup: 'custom-swal-rect' }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});