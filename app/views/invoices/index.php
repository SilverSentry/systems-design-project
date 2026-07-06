<?php

/** @var string $urlCreate URL para el botón de creación */
/** @var array $invoices */
/** @var array $statusBadges */
/** @var float $bcvRate */

require __DIR__ . '/../layouts/head.php';
?>

<?php require __DIR__ . '/../layouts/sidebar.php'; ?>

<div id="page-content-wrapper" class="w-100">

    <?php require __DIR__ . '/../layouts/navbar.php'; ?>

    <!-- Contenido principal -->
    <div class="col-12 col-md-11 mx-auto mt-4 animate-fadeIn">
        <div class="card shadow border-0">
            <div class="card-header py-3 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                <h1 class="card-title mb-0 fw-bold"> <i class="bi bi-receipt me-2"></i> Facturación</h1>
                <div class="d-flex gap-3">
                    <a href="<?= $urlCreate ?>" class="btn btn-golden btn-golden-all btn-lg"><i class="bi bi-plus-lg"></i> Nueva Factura</a>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table id="tabla-facturas" class="table table-striped table-hover align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>Nro. Factura</th>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th>Método de Pago</th>
                                <th>Tasa BCV</th>
                                <th>Total USD</th>
                                <th>Total VES</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($invoices as $invoice): ?>
                                <?php
                                $statusName = strtolower($invoice['status_name'] ?? 'desconocido');
                                $statusClass = $statusBadges[$statusName] ?? 'text-bg-secondary';
                                $rate = floatval($invoice['tasa_bcv']);
                                $totalUsd = floatval($invoice['total_usd']);
                                $totalVes = $totalUsd * $rate;
                                ?>
                                <tr>
                                    <td class="fw-bold"><?= htmlspecialchars($invoice['numero_factura']) ?></td>
                                    <td><?= htmlspecialchars(ucfirst($invoice['client_name']) . ' ' . ucfirst($invoice['client_surname'])) ?></td>
                                    <td><?= htmlspecialchars(date('d/m/Y h:i A', strtotime($invoice['fecha']))) ?></td>
                                    <td><?= htmlspecialchars($invoice['payment_method_name']) ?></td>
                                    <td>Bs. <?= htmlspecialchars(number_format($rate, 2, ',', '.')) ?></td>
                                    <td class="fw-bold text-success">$<?= htmlspecialchars(number_format($totalUsd, 2, ',', '.')) ?></td>
                                    <td class="fw-bold">Bs. <?= htmlspecialchars(number_format($totalVes, 2, ',', '.')) ?></td>
                                    <td><span class="badge rounded-pill <?= $statusClass ?>"><?= htmlspecialchars(ucfirst($invoice['status_name'] ?? 'Desconocido')) ?></span></td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="<?= \App\Core\Paths::to('invoices/show?id=' . $invoice['id']) ?>" class="btn btn-outline-primary btn-sm"><i class="bi bi-eye"></i> Ver Factura</a>
                                            <?php if ($statusName !== 'anulada'): ?>
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-cancel-invoice" data-id="<?= $invoice['id'] ?>" data-number="<?= htmlspecialchars($invoice['numero_factura']) ?>"><i class="bi bi-x-circle"></i> Anular</button>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" disabled><i class="bi bi-x-circle"></i> Anulada</button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
