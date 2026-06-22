<?php
$title = 'Error de base de datos';
$bodyClass = 'db-error-page';
require __DIR__ . '/layouts/head.php';
?>

<div class="db-error-shell min-vh-100 py-4 py-lg-5">
    <div class="container h-100 d-flex align-items-center">
        <div class="row justify-content-center align-items-stretch w-100 g-4">
            <div class="col-12 col-lg-5 d-flex">
                <div class="db-error-hero w-100 p-4 p-md-5 rounded-4 overflow-hidden position-relative text-white shadow-lg">
                    <div class="db-error-glow db-error-glow-1"></div>
                    <div class="db-error-glow db-error-glow-2"></div>
                    <div class="position-relative h-100 d-flex flex-column justify-content-between text-black">
                        <div>
                            <span class="db-error-badge d-inline-flex align-items-center gap-2 mb-4">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                Incidencia crítica
                            </span>
                            <div class="db-error-icon mb-4">
                                <i class="bi bi-database-x"></i>
                            </div>
                            <h1 class="display-6 fw-bold mb-3">La base de datos no respondió como se esperaba</h1>
                            <p class="db-error-hero-text mb-0">
                                Puede tratarse de una instancia detenida, una base inexistente o un problema de credenciales.
                            </p>
                        </div>

                        <div class="db-error-meta mt-4">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-shield-check"></i>
                                <span>La aplicación quedó protegida con una salida controlada</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-clock-history"></i>
                                <span>Revisa la configuración y vuelve a intentar cuando esté disponible</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-7 d-flex">
                <div class="card db-error-card border-0 shadow-lg w-100">
                    <div class="card-body p-4 p-md-5 d-flex flex-column justify-content-center">
                        <p class="text-uppercase text-secondary fw-semibold mb-2 letter-spacing-1">Servicio no disponible</p>
                        <h2 class="fw-bold mb-3">No fue posible establecer conexión</h2>
                        <p class="text-muted mb-4 fs-5">
                            La base de datos no está disponible, no existe o la conexión falló. Verifica que MySQL esté en ejecución,
                            que el nombre de la base sea correcto y que las credenciales coincidan con la configuración del entorno.
                        </p>

                        <div class="db-error-list mb-4">
                            <div class="db-error-list-item">
                                <i class="bi bi-dash-circle-fill"></i>
                                <span>Confirma que el servicio de MySQL esté activo.</span>
                            </div>
                            <div class="db-error-list-item">
                                <i class="bi bi-dash-circle-fill"></i>
                                <span>Verifica el nombre de la base de datos y el host configurado.</span>
                            </div>
                            <div class="db-error-list-item">
                                <i class="bi bi-dash-circle-fill"></i>
                                <span>Reintenta cuando el servidor esté disponible.</span>
                            </div>
                        </div>

                        <div class="d-flex flex-column flex-sm-row gap-3">
                            <a href="javascript:location.reload();" class="btn btn-golden btn-golden-all btn-lg px-4">
                                Reintentar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/layouts/footer.php'; ?>