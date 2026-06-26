<?php
//Layout de sidebar para el panel administrativo
//Se incluye solo en las vistas que requieren el menú lateral

use App\Core\Paths;
use App\Core\Helpers;

?>
<!-- Esto es la capa que oscurece el fondo -->
<div id="sidebar-overlay" class="overlay"></div>

<div id="sidebar">
  <div class="sidebar-brand text-center d-flex flex-column align-items-center">
    <div class="logo-container">
      <img src="<?= Paths::asset('img/logo.png') ?>" alt="Logo Studio Ordo Stetic">
    </div>
    <h3 class="sidebar-title">STUDIO ORDO<br><span>STETIC</span></h3>
  </div>

  <hr class="sidebar-divider">

  <nav class="nav flex-column">
    <div class="list-group list-group-flush">

      <a href="<?= Paths::to('admin_dashboard') ?>" class="list-group-item <?= Helpers::activeClass('admin_dashboard'); ?>"><i class="bi bi-speedometer2"></i> Inicio</a>

      <a href="<?= Paths::to('employees') ?>" class="list-group-item list-group-item-action <?= Helpers::activeClass('employees'); ?>"><i class="bi bi-people me-2"></i>Empleados</a>

      <a href="<?= Paths::to('clients') ?>" class="list-group-item list-group-item-action <?= Helpers::activeClass('clients'); ?>"><i class="bi bi-person me-2"></i>Clientes</a>

      <a href="<?= Paths::to('appointments') ?>" class="list-group-item list-group-item-action <?= Helpers::activeClass('appointments'); ?>"><i class="bi bi-calendar me-2"></i>Citas</a>

      <a href="<?= Paths::to('services') ?>" class="list-group-item list-group-item-action <?= Helpers::activeClass('services'); ?>"><i class="bi bi-bag-plus"></i>Servicios</a>

      <a href="<?= Paths::to('inventory') ?>" class="list-group-item list-group-item-action <?= Helpers::activeClass('inventory'); ?>"><i class="bi bi-box-seam me-2"></i>Inventario</a>

    </div>
  </nav>
</div>