<?php
//Layout de navbar superior para el panel administrativo
//Se incluye junto al sidebar en vistas de administración

use App\Core\Paths;
use App\Core\Session;

$user = Session::getUser() ?? [];
$roleId = $user['roleId'] ?? $user['id_rol'] ?? null;

$roleLabels = [
  1 => 'Administrador',
  2 => 'Empleado',
  3 => 'Cliente',
  4 => 'Administrador'
];

$roleLabel = $roleLabels[$roleId] ?? 'Usuario';
$userName = $user['name'] ?? 'Usuario';
?>
<div class="header">
  <div>
    <button class="btn btn-light shadow-sm me-3" id="menu-toggle">
      <i class="bi bi-list fs-5"></i>
    </button>
  </div>
  <div class="dropdown">
    <button
      class="btn btn-light shadow-sm dropdown-toggle d-flex align-items-center gap-2 px-3 py-2"
      type="button"
      data-bs-toggle="dropdown"
      aria-expanded="false">
      <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-dark text-white" style="width: 36px; height: 36px; font-size: 0.9rem;">
        <i class="bi bi-person-fill"></i>
      </span>
    </button>

    <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2 mt-2">

      <li class="px-3 py-2 border-bottom">
        <div class="fw-semibold text-dark"><?= htmlspecialchars($userName) ?></div>
        <small class="text-secondary"><?= htmlspecialchars($roleLabel) ?></small>
      </li>

      <li>
        <form method="post" action="<?= Paths::to('logout') ?>">
          <button type="submit" class="dropdown-item d-flex align-items-center gap-2 text-danger">
            <i class="bi bi-box-arrow-right"></i> Cerrar sesión</button>
        </form>
      </li>

    </ul>
  </div>
</div>