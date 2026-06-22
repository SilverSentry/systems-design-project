<?php

/** @var array $clients */
/** @var array $services */

use App\Core\Paths;
use App\Core\Session;

require __DIR__ . '/../layouts/head.php'; //Carga el head común

$appointmentError = Session::getFlash('appointment_error');
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

        <div class="col-12 col-md-9 mx-auto mt-4">
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
                                <select name="client_id" id="client_id" class="form-control">
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
                                <input type="date" name="date" class="form-control" id="date">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="time_start" class="form-label"><i class="bi bi-clock"></i> Hora inicio</label>
                                <input type="time" name="time_start" class="form-control" id="time_start">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="time_end" class="form-label"><i class="bi bi-clock-history"></i> Hora fin</label>
                                <input type="time" name="time_end" class="form-control" id="time_end">
                            </div>
                        </div>
                    </div>

                    <div class="row g-2 align-items-end">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="amount" class="form-label"><i class="bi bi-currency-dollar"></i> Monto total</label>
                                <input type="number" name="amount" class="form-control" id="amount" placeholder="Autocompletado desde los servicios" step="0.01" required readonly>
                                <span class="form-text" id="selectedServicesSummary">Servicios seleccionados: ninguno</span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <button type="button" class="btn btn-golden-all w-100" id="openServicesBtn"><i class="bi bi-list-check"></i> Elegir servicios</button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label"><i class="bi bi-card-text"></i> Notas</label>
                        <textarea name="notes" id="notes" class="form-control" rows="2" placeholder="Observaciones opcionales"></textarea>
                    </div>

                    <div class="row g-2 justify-content-end">
                        <div class="col-auto m-1">
                            <a href="<?= Paths::to('appointments') ?>" class="btn btn-second btn-lg"><i class="bi bi-arrow-left"></i> Regresar</a>
                        </div>

                        <div class="col-auto m-1">
                            <button type="submit" class="btn btn-golden-all btn-lg" id="submitBtn"><i class="bi bi-calendar-check"></i> Agendar</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <input type="hidden" name="service_ids" id="serviceIdsInput" value="">
</div>
</form>

<?php if (!empty($appointmentError) && is_array($appointmentError)): ?>
    <div id="appointment-error" data-field="<?= htmlspecialchars($appointmentError['field'] ?? '') ?>" data-message="<?= htmlspecialchars($appointmentError['message'] ?? '') ?>"></div>
<?php endif; ?>

<?php require __DIR__ . '/../partials/services-modal.php'; ?>

</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>