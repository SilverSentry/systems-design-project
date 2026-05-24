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
  <link href="<?= Paths::asset('css/styles.css') ?>" rel="stylesheet">
  <link href="<?= Paths::asset('Bootstrap-icons/bootstrap-icons.css') ?>" rel="stylesheet">
  <title>Panel de Administración</title>
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

<body style="background-color: var(--color-gris-claro);">

  <!-- Esto es la capa que oscurece el fondo -->
  <div id="sidebar-overlay" class="overlay"></div>

  <div class="d-flex" id="wrapper">

    <!-- Sidebar -->
    <div id="sidebar" class="toggled">
      <div class="sidebar-brand text-center">
        <img src="<?= Paths::asset('img/logo.png') ?>" alt="Logo" class="mb-2" style="width: 130px;">
        <h3 style="color: #c29c55;">STUDIO ORDO<br>STETIC</h3>
      </div>
      <hr>

      <nav class="nav flex-column">
        <div class="list-group list-group-flush">
          <a href="#" class="list-group-item list-group-item-action">
            <i class="bi bi-people me-2"></i>Usuarios
          </a>
          <form method="post" action="index.php">
            <input type="hidden" name="action" value="logout">
            <button type="submit" class="btn btn-danger"><i class="bi bi-box-arrow-right"></i> Cerrar Sesión</button>
          </form>
        </div>
      </nav>
    </div>

    <div id="page-content-wrapper" class="w-100">

      <!-- Header superior -->
      <div class="header">
        <div>
          <!-- Botón para mostrar/ocultar el sidebar -->
          <button class="btn btn-light shadow-sm me-3" id="menu-toggle">
            <i class="bi bi-list fs-5"></i>
          </button>
        </div>
        <div class="user-profile">
          <div class="text-end">
            <div class="fw-bold">Administrador</div>
          </div>
        </div>
      </div>

      <!-- Hero Section -->
      <header class="hero-section text-center d-flex align-items-center justify-content-center animate-fadeInUp">
        <div class="container position-relative px-3">
          <h1 class="fs-2 fs-md-1 display-5 fw-bold text-overlay text-white">Panel de Administración</h1>
          <h4 class="fs-5 fs-md-4 text-white text-overlay">Bienvenido, <?= ucfirst($user['name']); ?></h4>
        </div>
      </header>

      <div class="container-fluid p-4">

        <!-- Tarjetas de Estadísticas -->
        <div class="row g-3 mb-4 justify-content-center">
          <div class="col-md-3">
            <div class="card stat-card text-black">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h6 class="text-uppercase mb-1 opacity-75">Clientes atendidos</h6>
                    <h3 class="fw-bold mb-0">0</h3>
                  </div>
                  <div class="stat-icon bg-primary text-white">
                    <i class="bi bi-people fs-1 opacity-50"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card stat-card text-black">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h6 class="text-uppercase mb-1 opacity-75">Ingresos</h6>
                    <h3 class="fw-bold mb-0">$0</h3>
                  </div>
                  <div class="stat-icon bg-success text-white">
                    <i class="bi bi-currency-dollar fs-1 opacity-50"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card stat-card text-dark">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h6 class="text-uppercase mb-1 opacity-75">Reservaciones</h6>
                    <h3 class="fw-bold mb-0">0</h3>
                  </div>
                  <div class="stat-icon bg-warning text-black">
                    <i class="bi bi-bag fs-1 opacity-50"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!--
          <div class="col-md-3">
            <div class="card stat-card text-black">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h6 class="text-uppercase mb-1 opacity-75">Pendientes</h6>
                    <h3 class="fw-bold mb-0">0</h3>
                  </div>
                  <div class="stat-icon bg-danger text-white">
                    <i class="bi bi-check2-square fs-1 opacity-50"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          -->
        </div>

      </div>

    </div>

  </div>

  <!-- Overlay para móvil -->
  <div class="overlay" id="overlay"></div>

  <script src="<?= Paths::asset("js/dashboard.js") ?>"></script>
</body>

</html>