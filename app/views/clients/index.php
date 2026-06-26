<?php
/** @var array $clients Arreglo de clientes provisto por ClientController */
/** @var string $urlCreate URL para el botón de creación */
/** @var string $urlEdit URL base para el formulario de edición */

//La carpeta de layouts está en app/views/layouts, por lo que subimos un nivel desde app/views/employees
require __DIR__ . '/../layouts/head.php';
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
    <div class="col-12 col-md-11 mx-auto mt-4 animate-fadeIn">
        <div class="card shadow border-0">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h1 class="card-title mb-0 fw-bold"><i class="bi bi-person me-2"></i>Clientes</h1>
                <a href="<?= $urlCreate; ?>" class="btn btn-golden btn-golden-all btn-lg"><i class="bi bi-plus-lg"></i> Agregar cliente</a>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table id="tabla-clientes" class="table table-striped table-hover align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Télefono</th>
                                <th>Cédula</th>
                                <th>Edad</th>
                                <th>Género</th>
                                <th>Rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clients as $client):
                                $roleName = strtolower($client['rol_nombre'] ?? $client['id_rol'] ?? 'desconocido');
                                $roleClass = $roleBadges[$roleName] ?? 'text-bg-secondary';
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($client['id']) ?></td>
                                    <td><?= htmlspecialchars(ucfirst($client['nombre'])) ?></td>
                                    <td><?= htmlspecialchars(ucfirst($client['apellido'])) ?></td>
                                    <td><?= htmlspecialchars($client['telefono']) ?></td>
                                    <td><?= htmlspecialchars($client['dni']) ?: 'Sin cédula' ?></td>
                                    <td><?= htmlspecialchars($client['edad']) ?></td>
                                    <td><?= htmlspecialchars(ucfirst($client['genero'])) ?></td>
                                    <td><span class="badge rounded-pill <?= $roleClass ?>"><?= htmlspecialchars(ucfirst($client['rol_nombre'] ?? $client['id_rol'] ?? 'Desconocido')) ?></span></td>
                                    <td>
                                        <!-- Aquí puede ir un botón real de edición/eliminación cuando se agregue esa funcionalidad -->
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