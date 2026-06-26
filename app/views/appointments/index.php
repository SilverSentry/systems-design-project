<?php

/** @var string $urlCreate URL para el botón de creación */
/** @var string $urlServices URL para el botón de servicios */
/** @var array $appointments */
/** @var array $appointment */

require __DIR__ . '/../layouts/head.php';
?>

<?php require __DIR__ . '/../layouts/sidebar.php'; ?>

<div id="page-content-wrapper" class="w-100">

    <?php require __DIR__ . '/../layouts/navbar.php'; ?>

    <!-- Contenido principal -->
    <div class="col-12 col-md-11 mx-auto mt-4 animate-fadeIn">
        <div class="card shadow border-0">
            <div class="card-header py-3 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                <h1 class="card-title mb-0 fw-bold"> <i class="bi bi-calendar me-2"></i> Citas</h1>
                <div class="d-flex gap-3">
                    <a href="<?= $urlServices ?>" class="btn btn-second btn-lg"><i class="bi bi-list"></i> Ver servicios</a>
                    <a href="<?= $urlCreate ?>" class="btn btn-golden btn-golden-all btn-lg"><i class="bi bi-plus-lg"></i> Agendar cita</a>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table id="tabla-citas" class="table table-striped table-hover align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Creada</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Servicios</th>
                                <th>Estado</th>
                                <th>Notas</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($appointments as $appointment): ?>
                                <?php
                                $statusName = strtolower($appointment['estado_nombre'] ?? 'desconocido');
                                $statusClass = $statusBadges[$statusName] ?? 'text-bg-secondary';
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($appointment['id']) ?></td>
                                    <td><?= htmlspecialchars(ucfirst($appointment['nombre']) . ' ' . ucfirst($appointment['apellido'])) ?></td>
                                    <td><?= htmlspecialchars(date('d/m/Y', strtotime($appointment['creada']))) ?></td>
                                    <td><?= htmlspecialchars(date('d/m/Y', strtotime($appointment['fecha']))) ?></td>
                                    <td><?= htmlspecialchars(substr($appointment['hora_inicio'], 0, 5) . ' - ' . substr($appointment['hora_fin'], 0, 5)) ?></td>
                                    <td>
                                        <?php if (!empty($appointment['servicios'])): ?>
                                            <button type="button" class="btn btn-outline-info btn-sm btn-view-services" data-services="<?= htmlspecialchars($appointment['servicios'], ENT_QUOTES) ?>">
                                                Ver detalles
                                            </button>
                                        <?php else: ?>
                                            <span class="text-muted">Sin servicios</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><span class="badge rounded-pill <?= $statusClass ?>"><?= htmlspecialchars(ucfirst($appointment['estado_nombre'] ?? 'Desconocido')) ?></span></td>
                                    <td><?= htmlspecialchars($appointment['notas'] ?: 'Sin notas') ?></td>
                                    <td>
                                        <button type="button" class="btn btn-outline-primary btn-sm" disabled>Editar</button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" disabled>Eliminar</button>
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

<?php
$modalId = 'appointmentServicesModal';
$modalTitle = 'Servicios de la cita';
$modalBodyHtml = '<p class="mb-3">Estos son los servicios registrados para la cita:</p><div id="appointmentServicesList"></div>';
$modalFooterHtml = '<button type="button" class="btn btn-second" data-bs-dismiss="modal">Cerrar</button>';
$modalSize = '';
require __DIR__ . '/../partials/modal.php';
?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>