<?php
/** @var array $user */
/** @var array $users */

require __DIR__ . '/../layouts/head.php';
?>

<style>
  h1 {
    color: #000000c7 !important;
  }

  .card-header {
    border-bottom: 2px solid #e6c486 !important;
  }
</style>

<!-- Esto es la capa que oscurece el fondo -->
<div id="sidebar-overlay" class="overlay"></div>

<?php require __DIR__ . '/../layouts/sidebar.php'; ?>

<div id="page-content-wrapper" class="w-100">

  <?php require __DIR__ . '/../layouts/navbar.php'; ?>

  <!-- Contenido principal -->
  <div class="col-12 col-md-10 mx-auto mt-4">
    <div class="card shadow border-0">
      <div class="card-header py-3">
        <h1 class="card-tittle mb-0 fw-bold">Empleados</h1>
      </div>
      <div class="card-body p-4">
        <div class="table-responsive">
          <table id="tabla-empleados" class="table table-striped table-hover align-middle" style="width:100%">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($users as $employee): ?>
                <tr>
                  <td><?= htmlspecialchars($employee['id']) ?></td>
                  <td><?= htmlspecialchars(ucfirst($employee['nombre'])) ?></td>
                  <td><?= htmlspecialchars(ucfirst($employee['apellido'])) ?></td>
                  <td><?= htmlspecialchars($employee['email']) ?></td>
                  <td><?= htmlspecialchars(ucfirst($employee['rol_nombre'] ?? $employee['id_rol'] ?? 'Desconocido')) ?></td>
                  <td><?= htmlspecialchars(ucfirst($employee['estado_nombre'] ?? $employee['id_estado'] ?? 'Desconocido')) ?></td>
                  <td>
                    <!-- Aquí puede ir un botón real de edición/eliminación cuando se agregue esa funcionalidad -->
                    <button type="button" class="btn btn-sm btn-outline-secondary" disabled>Editar</button>
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

<!-- Overlay para móvil -->
<div class="overlay" id="overlay"></div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>