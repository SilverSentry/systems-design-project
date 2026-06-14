<?php

/** @var array $clients */
/** @var array $services */

use App\Core\Paths;

require __DIR__ . '/../layouts/head.php'; //Carga el head común
?>

<style>
    h1 {
        color: #000000c7 !important;
    }

    .card-header {
        border-bottom: 2px solid #e6c486 !important;
        box-shadow: 0 4px 6px rgba(255, 87, 51, 0.1) !important;
    }
</style>

<?php require __DIR__ . '/../layouts/sidebar.php'; ?>

<div id="page-content-wrapper" class="w-100">
    <?php require __DIR__ . '/../layouts/navbar.php'; ?>

    <!-- Contenido principal -->
    <form id="scheduleAppointmentForm" method="POST" action="<?= Paths::to('appointments/schedule') ?>">
        <input type="hidden" name="action" value="appointments/schedule">

        <div class="col-12 col-md-8 mx-auto mt-4">
            <div class="card shadow border-0">
                <div class="card-header py-3">
                    <h1 class="card-title mb-0 fw-bold">Agendar cita</h1>
                </div>
                <div class="card-body p-4">
                    <h5 class="mb-3">Complete los campos</h5>

                    <div class="row g-2">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="client_id" class="form-label"><i class="bi bi-person"></i> Cliente</label>
                                <select name="client_id" id="client_id" class="form-control" required>
                                    <option value="" selected disabled>Seleccione un cliente</option>
                                    <?php foreach ($clients as $client): ?>
                                        <option value="<?= htmlspecialchars($client['id']) ?>"><?= htmlspecialchars(ucfirst($client['nombre']) . ' ' . ucfirst($client['apellido'])) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="date" class="form-label"><i class="bi bi-calendar"></i> Fecha</label>
                                <input type="date" name="date" class="form-control" id="date" required>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="time_start" class="form-label"><i class="bi bi-clock"></i> Hora inicio</label>
                                <input type="time" name="time_start" class="form-control" id="time_start" required>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="time_end" class="form-label"><i class="bi bi-clock-history"></i> Hora fin</label>
                                <input type="time" name="time_end" class="form-control" id="time_end" required>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="amount" class="form-label"><i class="bi bi-currency-dollar"></i> Monto total</label>
                                <input type="number" name="amount" class="form-control" id="amount" placeholder="ej: 100.00" step="0.01" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="notes" class="form-label"><i class="bi bi-card-text"></i> Notas</label>
                                <textarea name="notes" id="notes" class="form-control" rows="2" placeholder="Observaciones opcionales"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2 justify-content-end">
                        <div class="col-auto m-1">
                            <a href="<?= Paths::to('appointments') ?>" class="btn btn-second btn-lg"><i class="bi bi-box-arrow-in-left"></i> Regresar</a>
                        </div>

                        <div class="col-auto m-1">
                            <button type="button" class="btn btn-golden-all btn-lg" id="openServicesBtn"><i class="bi bi-arrow-right"></i> Continuar</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <input type="hidden" name="service_ids" id="serviceIdsInput" value="">
</div>
</form>

<!-- Modal de selección de servicios -->
<div class="modal fade" id="servicesModal" tabindex="-1" aria-labelledby="servicesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="servicesModalLabel">Seleccionar servicios</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p>Marca los servicios que el cliente desea realizar y luego confirma para agendar la cita.</p>

                <div class="row g-3">
                    <?php foreach ($services as $service): ?>
                        <div class="col-md-6">
                            <div class="form-check border rounded p-3">
                                <input class="form-check-input service-checkbox" type="checkbox" value="<?= htmlspecialchars($service['id']) ?>" id="service-<?= htmlspecialchars($service['id']) ?>">
                                <label class="form-check-label fw-bold" for="service-<?= htmlspecialchars($service['id']) ?>">
                                    <?= htmlspecialchars($service['nombre']) ?>
                                </label>
                                <p class="mb-0 text-muted small"><?= htmlspecialchars($service['descripcion']) ?></p>
                                <p class="mb-0 mt-2"><strong>Precio:</strong> <?= htmlspecialchars(number_format($service['precio'], 2, ',', '.')) ?> USD</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-golden-all" id="confirmServicesBtn">Confirmar servicios</button>
            </div>
        </div>
    </div>
</div>

</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>