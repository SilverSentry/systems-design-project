<?php

/** @var array $services */

use App\Core\Paths;

require __DIR__ . '/../layouts/head.php';
?>

<?php require __DIR__ . '/../layouts/sidebar.php'; ?>

<div id="page-content-wrapper" class="w-100">

    <?php require __DIR__ . '/../layouts/navbar.php'; ?>

    <div class="col-12 col-md-11 mx-auto mt-4">
        <div class="card shadow border-0">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h1 class="card-title mb-0 fw-bold">Servicios</h1>
                <div class="d-flex gap-3">
                    <a href="<?= Paths::to('appointments') ?>" class="btn btn-second btn-lg"><i class="bi bi-arrow-left"></i> Regresar</a>
                    <a href="<?= Paths::to('appointments/create') ?>" class="btn btn-golden-all btn-lg"><i class="bi bi-calendar-check"></i> Agendar</a>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table id="tabla-servicios" class="table table-striped table-hover align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($services as $service): ?>
                                <tr>
                                    <td><?= htmlspecialchars($service['id']) ?></td>
                                    <td><?= htmlspecialchars($service['nombre']) ?></td>
                                    <td><?= htmlspecialchars($service['descripcion']) ?></td>
                                    <td><?= htmlspecialchars(number_format($service['precio'], 2, ',', '.')) ?></td>
                                    <td>
                                        <?php if (isset($service['estado']) && intval($service['estado']) === 1): ?>
                                            <span class="badge rounded-pill text-bg-success">Activo</span>
                                        <?php else: ?>
                                            <span class="badge rounded-pill text-bg-secondary">Inactivo</span>
                                        <?php endif; ?>
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
