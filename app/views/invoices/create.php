<?php

/** @var array $appointments */
/** @var array $paymentMethods */
/** @var float $bcvRate */
/** @var string $bcvRateFormatted */
/** @var string $bcvRateDate */

require __DIR__ . '/../layouts/head.php';
?>

<?php require __DIR__ . '/../layouts/sidebar.php'; ?>

<div id="page-content-wrapper" class="w-100">

    <?php require __DIR__ . '/../layouts/navbar.php'; ?>

    <div class="col-12 col-md-11 mx-auto mt-4 animate-fadeIn">
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="<?= \App\Core\Paths::to('invoices') ?>" class="btn btn-second"><i class="bi bi-arrow-left"></i> Volver</a>
            <h1 class="h3 mb-0 fw-bold"><i class="bi bi-file-earmark-plus me-2"></i> Registrar Factura</h1>
        </div>

        <form id="createInvoiceForm" method="post" action="<?= \App\Core\Paths::to('invoices/store') ?>">
            <div class="row g-4">
                
                <!-- Columna Izquierda: Selección de Cita, Cliente e Items -->
                <div class="col-12 col-lg-8">
                    
                    <!-- Tarjeta de Selección -->
                    <div class="card shadow border-0 mb-4">
                        <div class="card-header py-3">
                            <h5 class="card-title mb-0 fw-semibold"><i class="bi bi-calendar-event me-2"></i> Seleccionar Cita Pendiente</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label for="appointment_id" class="form-label fw-medium">Cita de Cliente *</label>
                                <select class="form-select form-control form-control-lg" id="appointment_id" name="appointment_id" required>
                                    <option value="" selected disabled>Seleccione una cita pendiente de cobro...</option>
                                    <?php foreach ($appointments as $appt): ?>
                                        <option value="<?= $appt['id'] ?>">
                                            Cita #<?= $appt['id'] ?> - <?= htmlspecialchars(ucfirst($appt['client_name']) . ' ' . ucfirst($appt['client_surname'])) ?> (<?= htmlspecialchars(date('d/m/Y', strtotime($appt['fecha']))) ?>) - $<?= htmlspecialchars(number_format($appt['monto_total'], 2, ',', '.')) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Por favor, seleccione una cita válida.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Tarjeta de Información de Cliente -->
                    <div class="card shadow border-0 mb-4 d-none animate-fadeIn" id="client-info-card">
                        <div class="card-header py-3 bg-light">
                            <h5 class="card-title mb-0 fw-semibold text-dark"><i class="bi bi-person-badge me-2"></i> Datos del Cliente</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-12 col-sm-4">
                                    <small class="text-muted d-block text-uppercase">Nombre Completo</small>
                                    <span class="fw-bold fs-5 text-dark" id="client-fullname">-</span>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <small class="text-muted d-block text-uppercase">Cédula / DNI</small>
                                    <span class="fw-bold fs-5 text-dark" id="client-dni">-</span>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <small class="text-muted d-block text-uppercase">Teléfono</small>
                                    <span class="fw-bold fs-5 text-dark" id="client-phone">-</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tarjeta de Servicios de la Cita -->
                    <div class="card shadow border-0 mb-4 d-none animate-fadeIn" id="services-card">
                        <div class="card-header py-3 bg-light">
                            <h5 class="card-title mb-0 fw-semibold text-dark"><i class="bi bi-card-checklist me-2"></i> Servicios a Facturar</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="ps-4">Servicio</th>
                                            <th>Precio Unitario</th>
                                            <th>Cantidad</th>
                                            <th class="text-end pe-4">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="services-list">
                                        <!-- Se carga por AJAX -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Columna Derecha: Totales y Método de Pago -->
                <div class="col-12 col-lg-4">
                    <div class="card shadow border-0 position-sticky" style="top: 20px;">
                        <div class="card-header py-3">
                            <h5 class="card-title mb-0 fw-semibold"><i class="bi bi-calculator me-2"></i> Resumen de Pago</h5>
                        </div>
                        <div class="card-body p-4">
                            
                            <!-- Totales en USD -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Subtotal:</span>
                                    <span class="fw-semibold text-dark" id="summary-subtotal">$0.00</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">IVA (16%):</span>
                                    <span class="fw-semibold text-dark" id="summary-iva">$0.00</span>
                                </div>
                                <hr class="my-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="h5 mb-0 fw-bold">Total USD:</span>
                                    <span class="h4 mb-0 fw-bold text-success" id="summary-total-usd">$0.00</span>
                                </div>
                            </div>

                            <!-- Tasa BCV y Conversión a VES -->
                            <div class="p-3 bg-light rounded mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.7rem;">Tasa oficial BCV</small>
                                    <span class="badge bg-primary text-white" id="bcv-display">Bs. <?= $bcvRateFormatted ?></span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="fw-bold text-dark fs-5">Total VES:</span>
                                    <span class="fw-bold text-dark fs-4" id="summary-total-ves">Bs. 0,00</span>
                                </div>
                                <small class="text-secondary d-block mt-2 text-center" style="font-size: 0.65rem;">Actualizada: <?= $bcvRateDate ?></small>
                                <input type="hidden" id="bcv_rate_hidden" value="<?= $bcvRate ?>">
                            </div>

                            <!-- Método de Pago -->
                            <div class="mb-4">
                                <label for="payment_method_id" class="form-label fw-medium">Método de Pago *</label>
                                <select class="form-select form-control" id="payment_method_id" name="payment_method_id" required>
                                    <option value="" selected disabled>Seleccione método de pago...</option>
                                    <?php foreach ($paymentMethods as $method): ?>
                                        <option value="<?= $method['id'] ?>"><?= htmlspecialchars($method['nombre']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Por favor, seleccione un método de pago.</div>
                            </div>

                            <!-- Botón de Registro -->
                            <button type="submit" id="submitBtn" class="btn btn-golden btn-golden-all w-100 py-3 fs-5" disabled>
                                <i class="bi bi-receipt me-2"></i> Registrar Factura
                            </button>

                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
