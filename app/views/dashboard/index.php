<?php
//Dashboard de los empleados

/** @var array $user */

use App\Core\Paths;

require __DIR__ . '/../layouts/head.php'; //Carga el head común
?>

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

<!-- Esto es la capa que oscurece el fondo -->
<div id="sidebar-overlay" class="overlay"></div>

    <?php require __DIR__ . '/../layouts/sidebar.php'; ?>

    <div id="page-content-wrapper" class="w-100">

        <?php require __DIR__ . '/../layouts/navbar.php'; ?>

        <!-- Hero Section -->
        <header class="hero-section text-center d-flex align-items-center justify-content-center animate-fadeInUp">
            <div class="container position-relative px-3">
                <h1 class="fs-2 fs-md-1 display-5 fw-bold text-overlay text-white">Panel de Empleados</h1>
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
                    <div class="card stat-card text-black">
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

<!-- Overlay para móvil -->
<div class="overlay" id="overlay"></div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>