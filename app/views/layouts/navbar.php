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

//Leemos la última tasa guardada para mostrarla de entrada
$cacheFile = dirname(__DIR__, 3) . '/storage/tasa.json';
$savedTasa = "0.00";
$savedData = "No actualizada";

if (file_exists($cacheFile)) {
  $cacheData = json_decode(file_get_contents($cacheFile), true);
  $savedTasa = number_format($cacheData['bcv'] ?? 0, 2, ',', '.');
  $savedTimestamp = $cacheData['date'] ?? $cacheData['fecha'] ?? null;

  if ($savedTimestamp) {
    $savedData = (new \DateTimeImmutable($savedTimestamp, new \DateTimeZone('America/Caracas')))->format('d-m-Y h:i A');
  }
}
?>
<div class="header">

  <div>
    <button class="btn btn-light shadow-sm me-3 d-block d-md-none" id="menu-toggle">
      <i class="bi bi-list fs-5"></i>
    </button>
  </div>

  <div class="d-flex align-items-center gap-5">

    <div class="d-none d-md-flex align-items-center p-2 rounded shadow-sm text-dark" style="max-width: 280px;">
      <div class="me-3">
        <small class="text-muted d-block" style="font-size: 0.75rem;">TASA OFICIAL BCV</small>
        <span class="fw-bold fs-5">Bs. <span id="tasa-valor"><?php echo $savedTasa; ?></span></span>
        <small class="text-muted d-block" id="tasa-fecha" style="font-size: 0.65rem;">Ref: <?php echo $savedData; ?></small>
      </div>

      <button type="button" id="btn-actualizar-tasa" class="btn btn-sm btn-actualizar-tasa d-flex align-items-center justify-content-center btn-outline-primary rounded-circle p-2" title="Actualizar Tasa" style="width: 32px; height: 32px;">
        <i class="bi bi-arrow-clockwise refresh-icon"></i>
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

        <li class="d-md-none px-3 py-2 border-bottom">
          <div class="d-flex align-items-start justify-content-between gap-2">
            <div>
              <div class="fw-semibold text-dark">TASA OFICIAL BCV</div>
              <span class="fw-bold">Bs. <span id="tasa-valor-mobile"><?php echo $savedTasa; ?></span></span>
              <small class="text-secondary d-block mt-1" id="tasa-fecha-mobile">Ref: <?php echo $savedData; ?></small>
            </div>
            <button type="button" class="btn btn-sm btn-actualizar-tasa d-flex align-items-center justify-content-center btn-outline-primary rounded-circle p-2" title="Actualizar Tasa" style="width: 32px; height: 32px;">
              <i class="bi bi-arrow-clockwise refresh-icon"></i>
            </button>
          </div>
        </li>

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
</div>