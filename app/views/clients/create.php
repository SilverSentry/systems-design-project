<?php
/** @var array $user */

use App\Core\Paths;

require __DIR__ . '/../layouts/head.php'; //Carga el head común
?>

<style>
    h1 {
        color: #e6c486;
    }

    .card-header {
        border-bottom: 2px solid #e6c486 !important;
        box-shadow: 0 4px 6px rgba(255, 87, 51, 0.1) !important;
    }
</style>

<!-- Esto es la capa que oscurece el fondo -->
<div id="sidebar-overlay" class="overlay"></div>
<?php require __DIR__ . '/../layouts/sidebar.php'; ?>

<div id="page-content-wrapper" class="w-100">
    <?php require __DIR__ . '/../layouts/navbar.php'; ?>

    <!-- Contenido principal -->
    <form id="createClientForm" method="POST" action="<?= Paths::to('clients/register') ?>">
        <input type="hidden" name="action" value="clients/register">

        <div class="col-12 col-md-10 mx-auto mt-4">
            <div class="card shadow border-0">
                <div class="card-header py-3">
                    <h1 class="card-tittle mb-0 fw-bold">Agregar cliente</h1>
                </div>
                <div class="card-body p-4">
                    <h5 class="mb-3">Datos Personales</h5>

                    <div class="row g-2">
                        <!-- input nombre -->
                        <div class="col-md">
                            <div class="mb-3">
                                <label for="name" class="form-label"><i class="bi bi-person"></i> Nombre</label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Nombre">
                            </div>
                        </div>

                        <!-- input apellido -->
                        <div class="col-md">
                            <div class="mb-3">
                                <label for="surname" class="form-label"><i class="bi bi-person-fill"></i> Apellido</label>
                                <input type="text" name="surname" class="form-control" id="surname" placeholder="Apellido">
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <!-- input teléfono -->
                        <div class="col-md">
                            <div class="mb-3">
                                <label for="phone" class="form-label"><i class="bi bi-telephone"></i> Teléfono</label>
                                <input type="text" name="phone" class="form-control" id="phone" placeholder="ej: 0412345678">
                            </div>
                        </div>

                        <div class="col-md">
                            <div class="mb-3">
                                <label for="dni" class="form-label"><i class="bi bi-person-vcard"></i></i> Cédula de identidad</label>
                                <input type="text" name="dni" class="form-control" id="dni" placeholder="ej: 12345678">
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <!-- input fecha de nacimiento -->
                        <div class="col-md">
                            <div class="mb-3">
                                <label for="birthdate" class="form-label"><i class="bi bi-calendar"></i> Fecha de nacimiento</label>
                                <input type="date" name="birthdate" class="form-control" id="birthdate" placeholder="">
                            </div>
                        </div>

                        <!-- input género -->
                        <div class="col-md">
                            <div class="mb-3">
                                <label for="gender" class="form-label"><i class="bi bi-gender-ambiguous"></i> Género</label>
                                <select name="gender" class="form-control" id="gender">
                                    <option value="">Seleccionar</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h5 class="mb-3">Antecedentes clínicos</h5>

                    <div class="row g-3 align-items-end mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Tipo</label>
                            <select class="form-control" name="tipo_id" id="tipoAntecedente">
                                <option value="1">Alergia</option>
                                <option value="2">Patología</option>
                                <option value="3">Sustancia (Biopolímeros)</option>
                            </select>
                        </div>

                        <div class="col-md-6 position-relative">
                            <label class="form-label">Buscar en SNOMED CT</label>
                            <input type="text" class="form-control" name="term_name" id="searchSnomed" placeholder="Ej: Lidocaina, Diabetes, Silicona..." autocomplete="off">
                            <ul class="list-group position-absolute w-100 mt-1 shadow d-none" id="snomedResults" style="z-index: 1000; max-height: 180px; overflow-y: auto;"></ul>
                        </div>

                        <div class="col-md-3">
                            <button type="button" class="btn btn-secondary w-100" id="addAntecedenteBtn">Anexar</button>
                        </div>
                        
                    </div>

                    <div class="mb-4">
                        <label class="form-label d-block">Antecedentes clínicos detectados:</label>
                        <div id="listaAntecedentesAcumulados" class="p-3 border rounded bg-light" style="min-height: 50px;">
                            <span class="text-muted" id="vacioPlaceholder">Ningún antecedente seleccionado.</span>
                        </div>
                    </div>

                    <div id="hiddenInputsContainer"></div>

                    <div class="align-items-center d-flex">
                        <button type="submit" class="btn btn-golden btn-golden-all btn-lg" id="submitBtn">Registrar cliente</button>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>

<!-- Overlay para móvil -->
<div class="overlay" id="overlay"></div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>