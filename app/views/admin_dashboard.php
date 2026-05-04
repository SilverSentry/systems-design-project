<?php
//Si el usuario no ha iniciado sesión, se redirige al login
if (!Session::isLogged()) {
  redirect('login');
}

$user = Session::getUser(); //Obtenemos los datos del usuario logueado
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="<?= Paths::asset('Bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
  <link href="<?= Paths::asset('css/style.css') ?>" rel="stylesheet">
  <link href="<?= Paths::asset('Bootstrap-icons/bootstrap-icons.css') ?>" rel="stylesheet">
  <title>Welcome</title>
  <style>
    .hero-section {
      position: relative;
      height: 30vh;
      background: url("<?= Paths::asset('img/hero-bg2.png') ?>") no-repeat center center;
      background-size: cover;
      overflow: hidden;
    }

    .hero-section::before {
      content: "";
      position: absolute;
      inset: 0;
      background: inherit;
      filter: blur(3px) brightness(0.8);
      transform: scale(1.05);
    }
  </style>
</head>

<body>

  <div class="d-flex" id="wrapper">

    <div id="page-content-wrapper" class="w-100">

      <!-- Navbar Superior -->
      <nav class="top-navbar navbar navbar-expand-lg navbar-light">
        <div class="collapse navbar-collapse">
          <ul class="navbar-nav ms-auto align-items-center">
            <li class="nav-item me-3">
              <a class="nav-link position-relative" href="#">
                <i class="bi bi-bell fs-5"></i>
              </a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                <img src="https://ui-avatars.com/api/?name=Admin&background=4e73df&color=fff" class="rounded-circle me-2" width="32" height="32" alt="Avatar">
                <span class="d-none d-md-inline">Administrador</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end shadow">
                <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Perfil</a></li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Ajustes</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li>
                  <form method="post" action="index.php">
                    <input type="hidden" name="action" value="logout">
                    <button type="submit" class="btn btn-danger"><i class="bi bi-box-arrow-right"></i> Cerrar Sesión</button>
                  </form>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </nav>

      <!-- Hero Section -->
      <header class="hero-section text-center d-flex align-items-center justify-content-center animate-fadeInUp">
        <div class="container position-relative px-3">
          <h1 class="fs-2 fs-md-1 display-5 fw-bold text-overlay text-white">Panel de Administración</h1>
          <h4 class="fs-5 fs-md-4 text-white text-overlay">Bienvenido, <?= ucfirst($user['name']); ?></h4>
        </div>
      </header>

      <div class="container-fluid p-4">

        <!-- Tarjetas de Estadísticas -->
        <div class="row g-3 mb-4">
          <div class="col-md-3">
            <div class="card stat-card bg-primary text-white">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h6 class="text-uppercase mb-1 opacity-75">Clientes atendidos</h6>
                    <h3 class="fw-bold mb-0">0</h3>
                  </div>
                  <i class="bi bi-people fs-1 opacity-50"></i>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card stat-card bg-success text-white">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h6 class="text-uppercase mb-1 opacity-75">Ingresos</h6>
                    <h3 class="fw-bold mb-0">$0</h3>
                  </div>
                  <i class="bi bi-currency-dollar fs-1 opacity-50"></i>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card stat-card bg-warning text-dark">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h6 class="text-uppercase mb-1 opacity-75">Reservaciones</h6>
                    <h3 class="fw-bold mb-0">0</h3>
                  </div>
                  <i class="bi bi-bag fs-1 opacity-50"></i>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card stat-card bg-danger text-white">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h6 class="text-uppercase mb-1 opacity-75">Pendientes</h6>
                    <h3 class="fw-bold mb-0">0</h3>
                  </div>
                  <i class="bi bi-check2-square fs-1 opacity-50"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

</body>

</html>