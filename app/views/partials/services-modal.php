<?php
/**
 * Partial para el modal de selección de servicios en agenda de citas
 * Depende de la variable $services disponible en la vista
 * @var array $services
 */
$modalId = 'servicesModal';
$modalTitle = 'Seleccionar servicios';
$modalSize = 'modal-lg';
ob_start();
?>
<p>Marca los servicios que el cliente desea realizar y luego confirma para agendar la cita.</p>
<div class="row g-3">
    <?php foreach ($services as $service): ?>
        <div class="col-md-6">
            <div class="form-check border rounded p-3">
                <input class="form-check-input service-checkbox" type="checkbox" value="<?= htmlspecialchars($service['id']) ?>" data-price="<?= htmlspecialchars($service['precio']) ?>" id="service-<?= htmlspecialchars($service['id']) ?>">
                <label class="form-check-label fw-bold" for="service-<?= htmlspecialchars($service['id']) ?>">
                    <?= htmlspecialchars($service['nombre']) ?>
                </label>
                <p class="mb-0 text-muted small"><?= htmlspecialchars($service['descripcion']) ?></p>
                <p class="mb-0 mt-2"><strong>Precio:</strong> <?= htmlspecialchars(number_format($service['precio'], 2, ',', '.')) ?> USD</p>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php
$modalBodyHtml = ob_get_clean();
$modalFooterHtml = '<button type="button" class="btn btn-second" data-bs-dismiss="modal">Cancelar</button>';
$modalFooterHtml .= '<button type="button" class="btn btn-golden-all" id="confirmServicesBtn">Aceptar</button>';
require __DIR__ . '/modal.php';
