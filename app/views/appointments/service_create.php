<?php

/** @var array|null $serviceError */

use App\Core\Paths;
use App\Core\Session;

require __DIR__ . '/../layouts/head.php';

$serviceError = Session::getFlash('service_error');
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

    <form id="createServiceForm" method="POST" action="<?= Paths::to('services/store') ?>">
        <input type="hidden" name="action" value="services/store">

        <div class="col-12 col-md-9 mx-auto mt-4 animate-fadeIn">
            <div class="card shadow border-0">
                <div class="card-header py-3">
                    <h1 class="card-title mb-0 fw-bold">Agregar servicio</h1>
                </div>
                <div class="card-body p-4">
                    <h5 class="mb-3">Datos del servicio</h5>

                    <div class="row g-2">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="nombre" class="form-label"><i class="bi bi-tag"></i> Nombre</label>
                                <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre del servicio" required>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="descripcion" class="form-label"><i class="bi bi-card-text"></i> Descripción</label>
                                <textarea name="descripcion" id="descripcion" class="form-control" rows="3" placeholder="Descripción breve del servicio"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="precio" class="form-label"><i class="bi bi-currency-dollar"></i> Precio</label>
                                <input type="number" name="precio" id="precio" class="form-control" placeholder="0.00" step="0.01" min="0" required>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2 justify-content-end">
                        <div class="col-auto m-1">
                            <a href="<?= Paths::to('services') ?>" class="btn btn-second btn-lg"><i class="bi bi-arrow-left"></i> Regresar</a>
                        </div>

                        <div class="col-auto m-1">
                            <button type="submit" class="btn btn-golden-all btn-lg"><i class="bi bi-save"></i> Guardar servicio</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <?php if (!empty($serviceError) && is_array($serviceError)): ?>
        <div id="service-error" data-field="<?= htmlspecialchars($serviceError['field'] ?? '') ?>" data-message="<?= htmlspecialchars($serviceError['message'] ?? '') ?>"></div>
    <?php endif; ?>

</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
