<?php
if (!Session::isLogged()) {
  redirect('login');
}

$user = Session::getUser(); //Obtenemos los datos del usuario logueado

//Cargamos todos los usuarios desde el modelo para mostrar en la tabla
$userModel = new User();
$users = $userModel->getAll();

//Título
$title = 'Panel de Empleados';
//Aquí se colocan clases específicas para esta vista si es necesario
$bodyClass = 'layout-footer';
//Aquí se colocan scripts específicos para esta vista si es necesario
$extraScripts = ['DataTables/jquery-3.7.0.min.js', 'DataTables/jquery.dataTables.min.js', 'DataTables/dataTables.bootstrap5.min.js', 'js/sidebar.js', 'js/employees.js'];

//La carpeta de layouts está en app/views/layouts, por lo que subimos un nivel desde app/views/employees
require __DIR__ . '/../layouts/head.php';
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
                  <td><?= htmlspecialchars($employee['nombre']) ?></td>
                  <td><?= htmlspecialchars($employee['apellido']) ?></td>
                  <td><?= htmlspecialchars($employee['email']) ?></td>
                  <td>ekide</td>
                  <td>Activo</td>
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