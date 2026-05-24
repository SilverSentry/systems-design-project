<?php
if (!Session::isLogged()) {
  redirect('login');
}

$user = Session::getUser(); //Obtenemos los datos del usuario logueado

//Cargamos todos los usuarios desde el modelo para mostrar en la tabla
$userModel = new User();
$users = $userModel->getAll();

//Título
$title = 'Panel de Administración';
//Aquí se colocan clases específicas para esta vista si es necesario
$bodyClass = '';
//Aquí se colocan scripts específicos para esta vista si es necesario
$extraScripts = ['DataTables/jquery-3.7.0.min.js', 'DataTables/jquery.dataTables.min.js', 'DataTables/dataTables.bootstrap5.min.js', 'js/sidebar.js', 'js/empleados.js'];

//La carpeta de layouts está en app/views/layouts, por lo que subimos un nivel desde app/views/empleados
require __DIR__ . '/../layouts/head.php';
?>

<!-- Esto es la capa que oscurece el fondo -->
<div id="sidebar-overlay" class="overlay"></div>

<?php require __DIR__ . '/../layouts/sidebar.php'; ?>

<div id="page-content-wrapper" class="w-100">

  <?php require __DIR__ . '/../layouts/navbar.php'; ?>

  <div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="h3 mb-0 text-gray-800">Empleados</h1>
    </div>

    <table id="example" class="table table-striped table-hover align-middle mb-0" style="width:100%">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Apellido</th>
          <th>Email</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $employee): ?>
          <tr>
            <td><?= htmlspecialchars($employee['id_usuario']) ?></td>
            <td><?= htmlspecialchars($employee['nombre']) ?></td>
            <td><?= htmlspecialchars($employee['apellido']) ?></td>
            <td><?= htmlspecialchars($employee['email']) ?></td>
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

  <!-- Overlay para móvil -->
  <div class="overlay" id="overlay"></div>

  <?php require __DIR__ . '/../layouts/footer.php'; ?>