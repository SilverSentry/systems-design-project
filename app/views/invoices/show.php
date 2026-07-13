<?php

/** @var array $invoice */
/** @var array $invoiceDetails */

use App\Core\Paths;

require __DIR__ . '/../layouts/head.php';
?>

<?php require __DIR__ . '/../layouts/sidebar.php'; ?>

<style>
/* Custom style for invoice printing */
@media print {
    body {
        background-color: #ffffff !important;
        color: #000000 !important;
    }
    .no-print {
        display: none !important;
    }
    #sidebar, .sidebar, .header, .footer, .overlay {
        display: none !important;
    }
    #page-content-wrapper {
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
    }
    .invoice-card {
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    .container-fluid, .col-12, .col-md-11 {
        width: 100% !important;
        max-width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
    }
}

.invoice-header {
    border-bottom: 2px solid #eaeaea;
    padding-bottom: 20px;
}

.invoice-logo {
    max-height: 80px;
    object-fit: contain;
}

.watermark-void {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(-30deg);
    font-size: 5rem;
    font-weight: 800;
    color: rgba(220, 53, 69, 0.15);
    border: 8px solid rgba(220, 53, 69, 0.15);
    padding: 10px 30px;
    border-radius: 15px;
    z-index: 10;
    pointer-events: none;
    user-select: none;
}
</style>

<div id="page-content-wrapper" class="w-100">

    <?php require __DIR__ . '/../layouts/navbar.php'; ?>

    <div class="col-12 col-md-11 mx-auto mt-4 mb-5 animate-fadeIn">
        
        <!-- Botones de Control no-print -->
        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <a href="<?= \App\Core\Paths::to('invoices') ?>" class="btn btn-second"><i class="bi bi-arrow-left"></i> Volver a Facturas</a>
            <div class="d-flex gap-2">
                <button onclick="window.print();" class="btn btn-golden-all btn-lg"><i class="bi bi-printer"></i> Imprimir/Descargar</button>
                <a href="<?= \App\Core\Paths::to('invoices/download?id=' . $invoice['id']) ?>" class="btn btn-golden btn-golden-all btn-lg"><i class="bi bi-file-earmark-pdf"></i> Descargar PDF</a>
            </div>
        </div>

        <!-- Cuerpo de Factura -->
        <div class="card shadow border-0 p-4 p-md-5 invoice-card position-relative bg-white overflow-hidden">
            
            <!-- Marca de agua si está Anulada -->
            <?php if (strtolower($invoice['status_name']) === 'anulada'): ?>
                <div class="watermark-void">ANULADA</div>
            <?php endif; ?>

            <!-- Encabezado de Factura -->
            <div class="row invoice-header align-items-center mb-4">
                <div class="col-12 col-md-6 mb-3 mb-md-0 d-flex align-items-center gap-3">
                    <div class="logo-container">
                        <img src="<?= Paths::asset('img/logo.png') ?>" alt="Logo Studio Ordo Stetic">
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0 text-dark">STUDIO ORDO STETIC</h3>
                        <small class="text-secondary d-block">RIF: J-0000000-0</small>
                        <small class="text-secondary d-block">Avenida Santa Rosa, Valencia, Edo. Carabobo.</small>
                        <small class="text-secondary d-block">Teléfono: (0412) 000-000</small>
                    </div>
                </div>
                <div class="col-12 col-md-6 text-md-end">
                    <span class="badge rounded-pill <?= strtolower($invoice['status_name']) === 'pagada' ? 'bg-success' : 'bg-danger' ?> text-white fs-6 mb-2">
                        <?= strtoupper($invoice['status_name']) ?>
                    </span>
                    <h2 class="fw-bold text-dark mb-1"><?= htmlspecialchars($invoice['numero_factura']) ?></h2>
                    <p class="text-secondary mb-0"><strong>Fecha:</strong> <?= htmlspecialchars(date('d/m/Y h:i A', strtotime($invoice['fecha']))) ?></p>
                </div>
            </div>

            <!-- Información del Cliente & Cita -->
            <div class="row mb-4">
                <div class="col-12 col-md-6 mb-3 mb-md-0">
                    <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">Cliente</h5>
                    <p class="mb-1 text-dark"><strong>Nombre:</strong> <?= htmlspecialchars(ucfirst($invoice['client_name']) . ' ' . ucfirst($invoice['client_surname'])) ?></p>
                    <p class="mb-1 text-dark"><strong>Cédula:</strong> <?= htmlspecialchars($invoice['client_dni'] ?: 'S/D') ?></p>
                    <p class="mb-0 text-dark"><strong>Teléfono:</strong> <?= htmlspecialchars($invoice['client_phone']) ?></p>
                </div>
                <div class="col-12 col-md-6">
                    <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">Detalles de Operación</h5>
                    <p class="mb-1 text-dark"><strong>Cita Relacionada:</strong> #<?= $invoice['id_cita'] ?></p>
                    <p class="mb-1 text-dark"><strong>Fecha de Cita:</strong> <?= htmlspecialchars(date('d/m/Y', strtotime($invoice['appointment_date']))) ?></p>
                    <p class="mb-0 text-dark"><strong>Atendido por:</strong> <?= htmlspecialchars(ucfirst($invoice['user_name']) . ' ' . ucfirst($invoice['user_surname'])) ?></p>
                </div>
            </div>

            <!-- Servicios Facturados -->
            <div class="mb-4">
                <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">Detalle de Servicios</h5>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>Servicio</th>
                                <th class="text-center" style="width: 100px;">Cant.</th>
                                <th class="text-end" style="width: 150px;">Precio Unit. (USD)</th>
                                <th class="text-end" style="width: 150px;">Total (USD)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($invoiceDetails as $detail): ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold text-dark"><?= htmlspecialchars($detail['service_name']) ?></div>
                                        <small class="text-secondary"><?= htmlspecialchars($detail['service_description'] ?: 'Sin descripción') ?></small>
                                    </td>
                                    <td class="text-center"><?= $detail['cantidad'] ?></td>
                                    <td class="text-end">$<?= number_format($detail['precio_unitario'], 2, ',', '.') ?></td>
                                    <td class="text-end fw-semibold text-dark">$<?= number_format($detail['total'], 2, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Resumen y Totales -->
            <div class="row justify-content-end">
                <div class="col-12 col-md-5">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td class="text-secondary pb-1">Subtotal USD:</td>
                                <td class="text-end text-dark fw-medium pb-1">$<?= number_format($invoice['subtotal_usd'], 2, ',', '.') ?></td>
                            </tr>
                            <tr>
                                <td class="text-secondary pb-1">IVA (16%):</td>
                                <td class="text-end text-dark fw-medium pb-1">$<?= number_format($invoice['iva_usd'], 2, ',', '.') ?></td>
                            </tr>
                            <tr class="border-top">
                                <td class="fw-bold text-dark pt-2 fs-5">Total USD:</td>
                                <td class="text-end fw-bold text-success pt-2 fs-5">$<?= number_format($invoice['total_usd'], 2, ',', '.') ?></td>
                            </tr>
                            <tr class="bg-light">
                                <td class="text-secondary py-2 fs-6 border-top">Tasa BCV:</td>
                                <td class="text-end text-secondary py-2 fs-6 border-top">Bs. <?= number_format($invoice['tasa_bcv'], 2, ',', '.') ?></td>
                            </tr>
                            <tr class="bg-light border-bottom">
                                <td class="fw-bold text-dark py-2 fs-5">Total VES:</td>
                                <td class="text-end fw-bold text-dark py-2 fs-5">Bs. <?= number_format($invoice['total_usd'] * $invoice['tasa_bcv'], 2, ',', '.') ?></td>
                            </tr>
                            <tr>
                                <td class="text-secondary pt-3">Método de Pago:</td>
                                <td class="text-end text-dark fw-bold pt-3"><?= htmlspecialchars($invoice['payment_method_name']) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mensaje de Cierre -->
            <div class="text-center mt-5 pt-4 border-top">
                <p class="mb-1 text-dark fw-medium">¡Gracias por preferir a Studio Ordo Stetic!</p>
                <small class="text-secondary">Documento no fiscal emitido por el sistema.</small>
            </div>

        </div>
    </div>

</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
