<?php

use App\Core\Session;
use App\Core\Paths;
use App\Models\Client;

if (!Session::isLogged()) {
  redirect('login');
}

$user = Session::getUser(); //Obtenemos los datos del usuario logueado

//Cargamos todos los usuarios desde el modelo para mostrar en la tabla
$userModel = new Client();
$clients = $userModel->read();

//Título
$title = 'Clientes';
//Aquí se colocan clases específicas para esta vista si es necesario
$bodyClass = 'layout-footer';
//Aquí se colocan scripts específicos para esta vista si es necesario
$extraScripts = ['DataTables/jquery-3.7.0.min.js', 'DataTables/jquery.dataTables.min.js', 'DataTables/dataTables.bootstrap5.min.js', 'js/sidebar.js', 'js/clients.js'];

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
        <h1 class="card-tittle mb-0 fw-bold">Clientes</h1>
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
                //1. Creamos un objeto DateTime con la fecha de nacimiento del cliente
                $birthdate = new DateTime($client['fecha_nacimiento']);
                //2. Creamos un objeto DateTime con la fecha actual del servidor
                $today = new DateTime();                
                //3. Calculamos la diferencia exacta entre ambas fechas
                $diff = $today->diff($birthdate);                
                //4. Extraemos el total de años transcurridos
                $age = $diff->y . ' años';
            ?>
                <tr>
                  <td><?= htmlspecialchars($client['id']) ?></td>
                  <td><?= htmlspecialchars($client['nombre']) ?></td>
                  <td><?= htmlspecialchars($client['apellido']) ?></td>
                  <td><?= htmlspecialchars($client['telefono']) ?></td>
                  <td><?= htmlspecialchars($client['dni']) ?></td>
                  <td><?= $age ?></td>
                  <td><?= htmlspecialchars($client['genero']) ?></td>
                  <td>Cliente</td>
                  <td>
                    <!-- Aquí puede ir un botón real de edición/eliminación cuando se agregue esa funcionalidad -->
                    <button type="button" class="btn btn-sm btn-outline-secondary" disabled>Editar</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" disabled>Eliminar</button>
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