<?php
/** @var array $user */
/** @var array $users */

require __DIR__ . '/../layouts/head.php';
?>

<style>
  h1 {
    color: #000000c7 !important;
  }

</style>

<?php require __DIR__ . '/../layouts/sidebar.php'; ?>

<div id="page-content-wrapper" class="w-100">

  <?php require __DIR__ . '/../layouts/navbar.php'; ?>

  <!-- Contenido principal -->
  <div class="col-12 col-md-11 mx-auto mt-4 animate-fadeIn">
    <div class="card shadow border-0">
      <div class="card-header py-3">
        <h1 class="card-tittle mb-0 fw-bold"><i class="bi bi-people me-2"></i>Empleados</h1>
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
              <?php foreach ($users as $employee):
                $roleName = strtolower($employee['rol_nombre'] ?? $employee['id_rol'] ?? 'desconocido');
                $statusName = strtolower($employee['estado_nombre'] ?? $employee['id_estado'] ?? 'desconocido');
                $roleClass = $roleBadges[$roleName] ?? 'text-bg-secondary';
                $statusClass = $statusBadges[$statusName] ?? 'text-bg-secondary';
              ?>
                <tr>
                  <td><?= htmlspecialchars($employee['id']) ?></td>
                  <td><?= htmlspecialchars(ucfirst($employee['nombre'])) ?></td>
                  <td><?= htmlspecialchars(ucfirst($employee['apellido'])) ?></td>
                  <td><?= htmlspecialchars($employee['email']) ?></td>
                  <td><span class="badge rounded-pill <?= $roleClass ?>"><?= htmlspecialchars(ucfirst($employee['rol_nombre'] ?? $employee['id_rol'] ?? 'Desconocido')) ?></span></td>
                  <td><span class="badge rounded-pill <?= $statusClass ?>"><?= htmlspecialchars(ucfirst($employee['estado_nombre'] ?? $employee['id_estado'] ?? 'Desconocido')) ?></span></td>
                  <td>
                    <!-- Aquí puede ir un botón real de edición/eliminación cuando se agregue esa funcionalidad -->
                    <button type="button" class="btn btn-outline-primary btn-sm" disabled>Editar</button>
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