<?php
//Layout de sidebar para el panel administrativo
//Se incluye solo en las vistas que requieren el menú lateral

use App\Core\Paths;

?>
<!-- Esto es la capa que oscurece el fondo -->
<div id="sidebar-overlay" class="overlay"></div>

<div id="sidebar" class="toggled">
  <div class="sidebar-brand text-center">
    <img src="<?= Paths::asset('img/logo.png') ?>" alt="Logo" class="mb-2" style="width: 130px;">
    <h3 style="color: #c29c55;">STUDIO ORDO<br>STETIC</h3>
  </div>
  <hr>

  <nav class="nav flex-column">
    <div class="list-group list-group-flush">
      <a href="<?= Paths::to('admin_dashboard') ?>" class="list-group-item list-group-item-action"><i class="bi bi-speedometer2 me-2"></i>Inicio</a>
      <a href="<?= Paths::to('employees') ?>" class="list-group-item list-group-item-action"><i class="bi bi-people me-2"></i>Empleados</a>
      <a href="<?= Paths::to('clients') ?>" class="list-group-item list-group-item-action"><i class="bi bi-person me-2"></i>Clientes</a>
      <a href="<?= Paths::to('appointments') ?>" class="list-group-item list-group-item-action"><i class="bi bi-calendar me-2"></i>Citas</a>
      <form method="post" action="<?= Paths::to('logout') ?>">
        <button type="submit" class="list-group-item list-group-item-action"><i class="bi bi-box-arrow-right"></i> Cerrar Sesión</button>
      </form>
    </div>
  </nav>
</div>
