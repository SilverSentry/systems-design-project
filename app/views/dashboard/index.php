<?php
//Dashboard de los empleados

/** @var array $user */

use App\Core\Paths;

require __DIR__ . '/../layouts/head.php'; //Carga el head común
?>

<style>
.hero-section {
    position: relative;
    border-radius: 80px;
    height: 20vh;
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

    <?php require __DIR__ . '/../layouts/sidebar.php'; ?>

    <div id="page-content-wrapper" class="w-100">

        <?php require __DIR__ . '/../layouts/navbar.php'; ?>

       <!-- Hero Section -->
    <div class="col-lg-11 mx-auto">
        <header class="hero-section d-flex align-items-center justify-content-beetween animate-fadeInUp">
            <div class="container position-relative px-3">
                <h1 class="fs-2 fs-md-1 display-5 fw-bold text-overlay text-white">Panel de Empleados</h1>
                <h4 class="fs-5 fs-md-4 text-white text-overlay">Bienvenido, <?= ucfirst($user['name']); ?></h4>
            </div>
        </header>
    </div>

    <div class="container-fluid p-4 dashboard-content animate-fadeIn animate-delay-1">

        <?php
        $clientsAttended = $clientsAttended ?? 0;
        $totalRevenue = $totalRevenue ?? 0;
        $appointmentsPending = $appointmentsPending ?? 0;
        $activeServices = $activeServices ?? 0;
        $todayAppointments = $todayAppointments ?? 0;
        ?>

        <div class="row g-3 mb-4">

            <div class="col-12 col-md-6 col-lg-3">
                <div class="card stat-card text-black animate-fadeInUp animate-delay-6 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase mb-1 opacity-75">Citas de hoy</h6>
                                <h3 class="fw-bold mb-0"><?= number_format($todayAppointments, 0, ',', '.') ?></h3>
                            </div>
                            <div class="stat-icon bg-info text-white rounded-circle p-3">
                                <i class="bi bi-calendar-day fs-1 opacity-75"></i>
                            </div>
                        </div>
                        <hr>
                        <span class="small text-muted">Reservadas para hoy</span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="card stat-card text-black animate-fadeInUp animate-delay-2 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase mb-1 opacity-75">Clientes atendidos</h6>
                                <h3 class="fw-bold mb-0"><?= number_format($clientsAttended, 0, ',', '.') ?></h3>
                            </div>
                            <div class="stat-icon bg-primary text-white rounded-circle p-3">
                                <i class="bi bi-people fs-1 opacity-75"></i>
                            </div>
                        </div>
                        <hr>
                        <span class="small text-muted">Clientes con al menos una cita registrada</span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="card stat-card text-black animate-fadeInUp animate-delay-3 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase mb-1 opacity-75">Ingresos</h6>
                                <h3 class="fw-bold mb-0">$<?= number_format($totalRevenue, 2, ',', '.') ?></h3>
                            </div>
                            <div class="stat-icon bg-success text-white rounded-circle p-3">
                                <i class="bi bi-currency-dollar fs-1 opacity-75"></i>
                            </div>
                        </div>
                        <hr>
                        <span class="small text-muted">Total acumulado de las citas</span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="card stat-card text-black animate-fadeInUp animate-delay-4 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase mb-1 opacity-75">Citas pendientes</h6>
                                <h3 class="fw-bold mb-0"><?= number_format($appointmentsPending, 0, ',', '.') ?></h3>
                            </div>
                            <div class="stat-icon bg-warning text-black rounded-circle p-3">
                                <i class="bi bi-calendar-check fs-1 opacity-75"></i>
                            </div>
                        </div>
                        <hr>
                        <span class="small text-muted">Citas programadas sin completar</span>
                    </div>
                </div>
            </div>

            <!--
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card stat-card text-black animate-fadeInUp animate-delay-5 h-100 d-flex flex-column justify-content-center align-items-center text-center">
                    <div class="card-body">
                        <h6 class="text-uppercase mb-2 opacity-75">Servicios disponibles</h6>
                        <h2 class="fw-bold"><?= number_format($activeServices, 0, ',', '.') ?></h2>
                        <p class="small mb-0 text-muted">Catálogo de tratamientos activos</p>
                    </div>
                </div>
            </div>
            -->

        </div>

        <div class="row g-3">

            <div class="col-12 col-lg-8">
                <div class="card bg-glass text-dark border-0 shadow-sm animate-fadeInUp animate-delay-2 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title fw-bold mb-0">Citas semanales</h5>
                        </div>

                        <div style="position: relative; height: 350px; width: 100%;">
                            <canvas id="weeklyAppointmentsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card bg-glass text-dark border-0 shadow-sm animate-fadeInUp animate-delay-3 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title fw-bold mb-0">Ingresos mensuales</h5>
                        </div>

                        <div style="position: relative; height: 350px; width: 100%;">
                            <canvas id="monthlyRevenueChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>