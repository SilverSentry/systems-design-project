<?php

/** @var array $user */
/** @var array $client */

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
    <form id="editClientForm" method="POST" action="<?= Paths::to('clients/edit') ?>">
        <input type="hidden" name="action" value="clients/edit">
        <input type="hidden" name="id" value="<?= htmlspecialchars($client['id'] ?? '') ?>">

        <div class="col-12 col-md-9 mx-auto mt-4 animate-fadeIn">
            <div class="card shadow border-0">
                <div class="card-header py-3">
                    <h1 class="card-title mb-0 fw-bold"><i class="bi bi-person"></i> Editar cliente</h1>
                </div>
                <div class="card-body p-4">
                    <h5 class="mb-3">Datos Personales</h5>

                    <div class="row g-2">
                        <!-- input nombre -->
                        <div class="col-md">
                            <div class="mb-3">
                                <label for="name" class="form-label"><i class="bi bi-person"></i> Nombre</label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Nombre" value="<?= htmlspecialchars($client['nombre'] ?? '') ?>">
                            </div>
                        </div>

                        <!-- input apellido -->
                        <div class="col-md">
                            <div class="mb-3">
                                <label for="surname" class="form-label"><i class="bi bi-person-fill"></i> Apellido</label>
                                <input type="text" name="surname" class="form-control" id="surname" placeholder="Apellido" value="<?= htmlspecialchars($client['apellido'] ?? '') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <!-- input teléfono -->
                        <div class="col-md">
                            <div class="mb-3">
                                <label for="phone" class="form-label"><i class="bi bi-telephone"></i> Teléfono</label>
                                <input type="text" name="phone" class="form-control" id="phone" placeholder="ej: 0412345678" value="<?= htmlspecialchars($client['telefono'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="col-md">
                            <div class="mb-3">
                                <label for="dni" class="form-label"><i class="bi bi-person-vcard"></i> Cédula de identidad</label>
                                <input type="text" name="dni" class="form-control" id="dni" placeholder="ej: 12345678" value="<?= htmlspecialchars($client['dni'] ?? '') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <!-- input fecha de nacimiento -->
                        <div class="col-md">
                            <div class="mb-3">
                                <label for="birthdate" class="form-label"><i class="bi bi-calendar"></i> Fecha de nacimiento</label>
                                <input type="date" name="birthdate" class="form-control" id="birthdate" value="<?= htmlspecialchars($client['fecha_nacimiento'] ?? '') ?>">
                            </div>
                        </div>

                        <!-- input género -->
                        <div class="col-md">
                            <div class="mb-3">
                                <label for="gender" class="form-label"><i class="bi bi-gender-ambiguous"></i> Género</label>
                                <select name="gender" class="form-control" id="gender">
                                    <option value="">Seleccionar</option>
                                    <option value="Masculino" <?= (isset($client['genero']) && $client['genero'] === 'Masculino') ? 'selected' : '' ?>>Masculino</option>
                                    <option value="Femenino" <?= (isset($client['genero']) && $client['genero'] === 'Femenino') ? 'selected' : '' ?>>Femenino</option>
                                    <option value="Otro" <?= (isset($client['genero']) && $client['genero'] === 'Otro') ? 'selected' : '' ?>>Otro</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row g-2 justify-content-end">
                        <div class="col-auto m-1">
                            <a href="<?= Paths::to('clients') ?>" class="btn btn-second btn-lg"><i class="bi bi-box-arrow-in-left"></i> Regresar</a>
                        </div>

                        <div class="col-auto m-1">
                            <button type="submit" class="btn btn-golden-all btn-lg" id="submitBtn"><i class="bi bi-person-check"></i> Actualizar cliente</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
