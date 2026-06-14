<?php
//Dashboard del administrador

/** @var array $user */

use App\Core\Paths;

require __DIR__ . '/../layouts/head.php'; //Carga el head común
?>

<style>
    .hero-section {
        position: relative;
        height: 30vh;
        background: url("<?= Paths::asset('img/hero-bg.png') ?>") no-repeat center center;
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

<?php require __DIR__ . '/../layouts/sidebar.php'; ?>

<div id="page-content-wrapper" class="w-100">

    <?php require __DIR__ . '/../layouts/navbar.php'; ?>

    <!-- Hero Section -->
    <header class="hero-section text-center d-flex align-items-center justify-content-center animate-fadeInUp">
        <div class="container position-relative px-3">
            <h1 class="fs-2 fs-md-1 display-5 fw-bold text-overlay text-white">Panel de Administración</h1>
            <h4 class="fs-5 fs-md-4 text-white text-overlay">Bienvenido, <?= ucfirst($user['name']); ?></h4>
        </div>
    </header>

    <div class="container-fluid p-4 dashboard-content animate-fadeIn animate-delay-1">

        <!-- Tarjetas de Estadísticas -->
        <div class="row g-3 mb-4 justify-content-center">
            <div class="col-md-3">
                <div class="card stat-card text-black animate-fadeInUp animate-delay-2">
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
                <div class="card stat-card text-black animate-fadeInUp animate-delay-3">
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
                <div class="card stat-card text-black animate-fadeInUp animate-delay-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase mb-1 opacity-75">Citas</h6>
                                <h3 class="fw-bold mb-0">0</h3>
                            </div>
                            <div class="stat-icon bg-warning text-black">
                                <i class="bi bi-bag fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row mt-4">

        <div class="col-md-6 mx-auto">
            <div class="card bg-glass text-dark border-0 shadow-sm animate-fadeInUp animate-delay-2">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3 text-center">
                        <i class="bi bi-people-fill me-2"></i> Rendimiento de empleados (citas atendidas)
                    </h5>

                    <div style="position: relative; height: 300px; width: 100%;">
                        <canvas id="employeePerformance"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card bg-glass text-dark border-0 shadow-sm animate-fadeInUp animate-delay-3">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3 text-center">
                        <i class="bi bi-people-fill me-2"></i> Estadísticas de clientes (citas por mes)
                    </h5>

                    <div style="position: relative; height: 300px; width: 100%;">
                        <canvas id="clientStats"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>