<?php
// Vista de login que utiliza el layout compartido para el head y footer.
$title = 'Inicio de Sesión';
$bodyClass = 'd-flex align-items-center min-vh-100 body-lr';
require __DIR__ . '/layouts/head.php';
?>

    <div class="container mt-3">

        <div class="row justify-content-center">

            <div class="col-lg-10">

                <div class="split-container">

                    <div class="login-section">
                        <div class="text-center">
                            <!-- <img src="<php echo URL_BASE; ?>assets/img/logo.png" alt="Logo" width="140" height="150"  class="img-fluid rounded-circle mt-3" style="display: block; margin: 0 auto;"> -->
                            <h1 class="fw-bold text-center text-black pt-1 mb-4">Iniciar Sesión</h1>
                        </div>

                        <div class="card-body p-2">

                            <!--<div class="alert alert-danger text-center mx-auto invisible mb-2" role="alert" id="errorContainer" style="min-height: 58px;">
                            </div>-->

                            <form method="POST" action="index.php" id="formLogin">

                                <input type="hidden" name="action" value="login">

                                <div class="mb-3">
                                    <label for="email" class="form-label"><i class="bi bi-envelope"></i> Correo electrónico</label>
                                    <input type="text" name="email" class="form-control" id="email" placeholder="usuario@ejemplo.com">
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label"><i class="bi bi-lock"></i> Contraseña</label>
                                    <div class="input-group">
                                        <input type="password" name="password" class="form-control" id="password" placeholder="••••••••">
                                        <!-- Botón para mostrar/ocultar la contraseña -->
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="bi bi-eye-slash"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn-golden btn" id="submitBtn"><i class="bi bi-box-arrow-in-right"></i> Ingresar</button>
                                </div>

                                <div class="text-center mb-0 pt-3">
                                    <span class="form-text">¿No estás registrado?</span>
                                    <a href="<?= Paths::to('register') ?>" class="link">Regístrate aquí</a>
                                </div>

                            </form>

                        </div>
                    </div>

                    <div class="brand-section">
                        <img src="<?= Paths::asset('img/logo.png') ?>" alt="Logo" width="350" height="350" class="img-fluid rounded-circle login-logo" style="display: block; margin: 0 auto;">
                    </div>

                </div>

            </div>

        </div>

    </div>

<script src="<?= Paths::asset('SweetAlert2/sweetalert2.all.min.js') ?>"></script>
<script src="<?= Paths::asset('js/login.js') ?>"></script>
</body>
</html>
