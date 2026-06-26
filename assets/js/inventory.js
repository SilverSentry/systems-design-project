document.addEventListener("DOMContentLoaded", function () {
    // 1. Inicializar DataTable
    const tableId = '#tabla-inventario';
    let tablaInventario = null;
    
    if (document.querySelector(tableId)) {
        tablaInventario = $(tableId).DataTable({
            language: {
                url: '/StudioOrdoStetic/assets/DataTables/es-ES.json'
            },
            order: [[1, 'asc']], // Ordenar por nombre del producto ascendente
            columnDefs: [
                { orderable: false, targets: [2, 7] } // Deshabilitar ordenación en descripción y acciones
            ]
        });
    }

    // Helper para escapar HTML en JS
    function escapeHtml(text) {
        if (!text) return '';
        return text
            .toString()
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // 2. Controladores de envío de formularios AJAX
    
    // FORMULARIO AGREGAR PRODUCTO
    const formAddProduct = document.getElementById("formAddProduct");
    if (formAddProduct) {
        formAddProduct.addEventListener("submit", async function (e) {
            e.preventDefault();
            
            // Validaciones Bootstrap
            if (!this.checkValidity()) {
                e.stopPropagation();
                this.classList.add('was-validated');
                return;
            }
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn ? submitBtn.innerHTML : 'Guardar Producto';
            
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...`;
            }
            
            const formData = new FormData(this);
            cleanAllInputs('#formAddProduct .form-control', 'is-invalid');
            
            try {
                const response = await fetch(window.AppBasePath + 'inventory/create', {
                    method: 'POST',
                    body: formData
                });
                
                if (!response.ok) throw new Error("Error de respuesta del servidor");
                const data = await response.json();
                
                if (data.status === 'success') {
                    // Cerrar modal
                    const modalEl = document.getElementById('modalAddProduct');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                    
                    showAlert('success', '¡Éxito!', data.message, '#28a745').then(() => {
                        window.location.reload();
                    });
                } else {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                    showToast('error', data.message);
                    
                    if (data.field === 'all') {
                        this.querySelectorAll('.form-control').forEach(input => input.classList.add('is-invalid'));
                    } else if (data.field) {
                        const input = document.getElementById('add_' + data.field);
                        if (input) input.classList.add('is-invalid');
                    }
                }
            } catch (error) {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
                console.error("Error: ", error);
                showToast('error', 'Error de procesamiento en el servidor.');
            }
        });
    }

    // FORMULARIO EDITAR PRODUCTO
    const formEditProduct = document.getElementById("formEditProduct");
    if (formEditProduct) {
        formEditProduct.addEventListener("submit", async function (e) {
            e.preventDefault();
            
            if (!this.checkValidity()) {
                e.stopPropagation();
                this.classList.add('was-validated');
                return;
            }
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn ? submitBtn.innerHTML : 'Guardar Cambios';
            
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...`;
            }
            
            const formData = new FormData(this);
            cleanAllInputs('#formEditProduct .form-control', 'is-invalid');
            
            try {
                const response = await fetch(window.AppBasePath + 'inventory/edit', {
                    method: 'POST',
                    body: formData
                });
                
                if (!response.ok) throw new Error("Error de respuesta del servidor");
                const data = await response.json();
                
                if (data.status === 'success') {
                    const modalEl = document.getElementById('modalEditProduct');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                    
                    showAlert('success', '¡Éxito!', data.message, '#28a745').then(() => {
                        window.location.reload();
                    });
                } else {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                    showToast('error', data.message);
                    
                    if (data.field === 'all') {
                        this.querySelectorAll('.form-control').forEach(input => input.classList.add('is-invalid'));
                    } else if (data.field) {
                        const input = document.getElementById('edit_' + data.field);
                        if (input) input.classList.add('is-invalid');
                    }
                }
            } catch (error) {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
                console.error("Error: ", error);
                showToast('error', 'Error de procesamiento en el servidor.');
            }
        });
    }

    // FORMULARIO REGISTRAR MOVIMIENTO
    const formMovement = document.getElementById("formMovement");
    if (formMovement) {
        formMovement.addEventListener("submit", async function (e) {
            e.preventDefault();
            
            if (!this.checkValidity()) {
                e.stopPropagation();
                this.classList.add('was-validated');
                return;
            }
            
            // Validar stock insuficiente en el cliente
            const selectProd = document.getElementById('mov_id_producto');
            const selectedOpt = selectProd.options[selectProd.selectedIndex];
            const stockActual = parseInt(selectedOpt.getAttribute('data-stock') || '0');
            const cantidad = parseInt(document.getElementById('mov_cantidad').value || '0');
            const tipoSalida = document.getElementById('mov_tipo_salida').checked;
            
            if (tipoSalida && cantidad > stockActual) {
                showToast('error', `Stock insuficiente. El stock actual es ${stockActual} unidades.`);
                document.getElementById('mov_cantidad').classList.add('is-invalid');
                return;
            }
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn ? submitBtn.innerHTML : 'Aplicar Ajuste';
            
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...`;
            }
            
            const formData = new FormData(this);
            cleanAllInputs('#formMovement .form-control, #formMovement .form-select', 'is-invalid');
            
            try {
                const response = await fetch(window.AppBasePath + 'inventory/movement', {
                    method: 'POST',
                    body: formData
                });
                
                if (!response.ok) throw new Error("Error de respuesta del servidor");
                const data = await response.json();
                
                if (data.status === 'success') {
                    const modalEl = document.getElementById('modalMovement');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                    
                    showAlert('success', '¡Ajuste Registrado!', data.message, '#28a745').then(() => {
                        window.location.reload();
                    });
                } else {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                    showToast('error', data.message);
                    
                    if (data.field === 'all') {
                        this.querySelectorAll('.form-control, .form-select').forEach(input => input.classList.add('is-invalid'));
                    } else if (data.field) {
                        const input = document.getElementById('mov_' + data.field);
                        if (input) input.classList.add('is-invalid');
                    }
                }
            } catch (error) {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
                console.error("Error: ", error);
                showToast('error', 'Error al registrar el ajuste de stock.');
            }
        });
    }

    // 3. Rellenar Modal de Edición dinámicamente
    $(document).on('click', '.btn-edit-product', function () {
        const id = $(this).attr('data-id');
        const nombre = $(this).attr('data-nombre');
        const descripcion = $(this).attr('data-descripcion');
        const stockActual = $(this).attr('data-sotck_actual');
        const stockMinimo = $(this).attr('data-stock_minimo');
        const precioCompra = $(this).attr('data-precio_compra');
        
        // Cargar inputs
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_nombre').value = nombre;
        document.getElementById('edit_descripcion').value = descripcion;
        document.getElementById('edit_sotck_actual').value = stockActual;
        document.getElementById('edit_stock_minimo').value = stockMinimo;
        document.getElementById('edit_precio_compra').value = precioCompra;
        
        // Resetear validaciones previas
        const form = document.getElementById('formEditProduct');
        form.classList.remove('was-validated');
        cleanAllInputs('#formEditProduct .form-control', 'is-invalid');
        
        // Mostrar modal
        const modal = new bootstrap.Modal(document.getElementById('modalEditProduct'));
        modal.show();
    });

    // 4. Acción de Eliminar Producto
    $(document).on('click', '.btn-delete-product', function () {
        const id = $(this).attr('data-id');
        const nombre = $(this).attr('data-nombre');
        
        Swal.fire({
            title: '¿Estás seguro?',
            text: `¿Desea desactivar el producto "${nombre}" del inventario?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            customClass: { popup: 'custom-swal-rect' }
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const response = await fetch(window.AppBasePath + 'inventory/delete', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `id=${id}`
                    });
                    
                    if (!response.ok) throw new Error("Error del servidor");
                    const data = await response.json();
                    
                    if (data.status === 'success') {
                        showAlert('success', '¡Eliminado!', data.message, '#28a745').then(() => {
                            window.location.reload();
                        });
                    } else {
                        showToast('error', data.message);
                    }
                } catch (error) {
                    console.error("Error: ", error);
                    showToast('error', 'Error al intentar eliminar el producto.');
                }
            }
        });
    });

    // 5. Cargar Historial de Movimientos de Stock
    const btnOpenHistory = document.getElementById('btn-open-history');
    if (btnOpenHistory) {
        btnOpenHistory.addEventListener('click', async function () {
            const tbody = document.getElementById('body-historial-movimientos');
            tbody.innerHTML = `
              <tr class="loader-row">
                <td colspan="6" class="text-center py-4">
                  <div class="spinner-border text-golden" role="status">
                    <span class="visually-hidden">Cargando historial...</span>
                  </div>
                </td>
              </tr>
            `;
            
            try {
                const response = await fetch(window.AppBasePath + 'api/inventory/history');
                if (!response.ok) throw new Error("Respuesta de red no conforme");
                const data = await response.json();
                
                if (data.status === 'success') {
                    tbody.innerHTML = '';
                    const history = data.history || [];
                    
                    if (history.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="6" class="text-center text-muted py-3">No se registran movimientos en la bitácora.</td></tr>`;
                        return;
                    }
                    
                    history.forEach(mov => {
                        const badgeClass = mov.tipo_movimiento === 'entrada' ? 'badge-stock-ok' : 'badge-stock-bajo';
                        const typeText = mov.tipo_movimiento === 'entrada' ? 'Entrada' : 'Salida';
                        const prefix = mov.tipo_movimiento === 'entrada' ? '+' : '-';
                        
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td><small class="text-dark fw-semibold">${mov.fecha_formateada || mov.created_at}</small></td>
                            <td class="fw-semibold text-dark">${escapeHtml(mov.producto_nombre)}</td>
                            <td><span class="badge rounded-pill ${badgeClass} px-3 py-2">${typeText}</span></td>
                            <td class="fw-bold fs-6 text-dark">${prefix}${mov.cantidad}</td>
                            <td><small class="text-secondary">${escapeHtml(mov.motivo)}</small></td>
                            <td><small class="text-dark">${escapeHtml(mov.usuario_nombre)}</small></td>
                        `;
                        tbody.appendChild(tr);
                    });
                } else {
                    tbody.innerHTML = `<tr><td colspan="6" class="text-center text-danger py-3">Error al cargar bitácora: ${data.message}</td></tr>`;
                }
            } catch (error) {
                console.error("Error: ", error);
                tbody.innerHTML = `<tr><td colspan="6" class="text-center text-danger py-3">Error al conectar con el servidor.</td></tr>`;
            }
        });
    }

    // 6. Limpieza de clases de validación en tiempo real al escribir
    const allFormControls = document.querySelectorAll('.form-control, .form-select');
    allFormControls.forEach(el => {
        el.addEventListener('input', function () {
            if (this.classList.contains('is-invalid')) {
                this.classList.remove('is-invalid');
            }
        });
        el.addEventListener('change', function () {
            if (this.classList.contains('is-invalid')) {
                this.classList.remove('is-invalid');
            }
        });
    });
});
