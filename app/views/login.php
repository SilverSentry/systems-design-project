<!DOCTYPE html>
<html>

<head>
    <title>Inicio de Sesión</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="<?= paths::asset('Bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= paths::asset('css/style.css') ?>" rel="stylesheet">
    <link href="<?= paths::asset('Bootstrap-icons/bootstrap-icons.min.css')?>" rel="stylesheet">
</head>

<body class="d-flex align-items-center min-vh-100">

    <div class="container mt-3">

        <div class="row justify-content-center">

            <div class="col-lg-10">

                <div class="split-container-login">

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
                                    <label for="email" class="form-label"><i class="bi bi-envelope-fill"> </i>Correo electrónico</label>
                                    <input type="text" name="email" class="form-control" id="email" placeholder="user@example.com">
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label"><i class="bi bi-lock-fill"></i> Contraseña</label>
                                    <input type="password" name="password" class="form-control" id="password" placeholder="********">
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn-golden btn" id="submitBtn">Ingresar</button>
                                </div>

                                <div class="text-center mb-0 pt-2">
                                    <span class="form-text">¿No estás registrado?</span></br>
                                    <a href="<?= paths::to('register') ?>" class="link">Regístrate aquí</a>
                                </div>

                            </form>

                        </div>
                    </div>

                    <div class="brand-section">
                        <img src="<?= paths::asset('img/logo.png')?>" alt="Logo" width="200" height="150" class="img-fluid rounded-circle login-logo" style="display: block; margin: 0 auto;">
                    </div>

                </div>

            </div>

        </div>

    </div>

    <script src="<?= paths::asset('SweetAlert2/sweetalert2.all.min.js') ?>"></script>
    <script src="<?= paths::asset('js/login.js') ?>"></script>
</body>

</html>