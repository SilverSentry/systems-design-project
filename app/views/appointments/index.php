<?php
/** @var string $urlCreate URL para el botón de creación */
/** @var array $appointments */
/** @var array $appointment */

require __DIR__ . '/../layouts/head.php';
?>

<?php require __DIR__ . '/../layouts/sidebar.php'; ?>

<div id="page-content-wrapper" class="w-100">

    <?php require __DIR__ . '/../layouts/navbar.php'; ?>

    <!-- Contenido principal -->
    <div class="col-12 col-md-10 mx-auto mt-4">
        <div class="card shadow border-0">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h1 class="card-title mb-0 fw-bold">Citas</h1>
                <a href="<?= $urlCreate ?>" class="btn btn-golden btn-golden-all btn-lg"><i class="bi bi-plus-lg"></i> Agendar cita</a>
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

<?php require __DIR__ . '/../layouts/footer.php'; ?>