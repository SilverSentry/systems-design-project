<!DOCTYPE html>
<html>

<head>
    <title>Registro de Usuario</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="<?= Paths::asset('Bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= Paths::asset('css/styles.css') ?>" rel="stylesheet">
    <link href="<?= Paths::asset('Bootstrap-icons/bootstrap-icons.min.css') ?>" rel="stylesheet">
</head>
</head>

<body class="d-flex align-items-center min-vh-100 body-lr">

    <div class="container mt-1">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="split-container">

                    <div class="register-section">
                        <div class="text-center mt-1">
                            <!-- <img src="<php echo URL_BASE; ?>assets/img/logo.png" alt="Logo" width="140" height="150"  class="img-fluid rounded-circle mt-3" style="display: block; margin: 0 auto;"> -->
                            <h1 class="fw-bold text-center text-black pt-1 mb-4">Registro</h1>
                        </div>



                        <div class="card-body p-2">

                            <!--<div class="alert alert-danger text-center mx-auto invisible mb-2" role="alert" id="errorContainer" style="min-height: 58px;">
                            </div>-->

                            <form method="post" action="index.php" id="formRegister">

                                <input type="hidden" name="action" value="register">

                                <!-- input nombre y apellido -->
                                <div class="row g-2">
                                    <div class="col-md">
                                        <div class="mb-3">
                                            <label for="name" class="form-label"><i class="bi bi-person"></i> Nombre</label>
                                            <input type="text" name="name" class="form-control" id="name" placeholder="Nombre">
                                        </div>
                                    </div>

                                    <div class="col-md">
                                        <div class="mb-3">
                                            <label for="surname" class="form-label"><i class="bi bi-person-fill"></i> Apellido</label>
                                            <input type="text" name="surname" class="form-control" id="surname" placeholder="Apellido">
                                        </div>
                                    </div>
                                </div>

                                <!-- input correo -->
                                <div class="mb-3">
                                    <label for="email" class="form-label"><i class="bi bi-envelope"></i> Correo electrónico</label>
                                    <input type="text" name="email" class="form-control" id="email" placeholder="usuario@ejemplo.com">
                                </div>

                                <!-- input contraseña y confirmar contraseña -->
                                <div class="row g-2 mb-2">
                                    <div class="col-md">
                                        <div class="mb-3">
                                            <label for="password" class="form-label"><i class="bi bi-lock"></i> Contraseña</label>
                                            <div class="input-group">
                                                <input type="password" name="password" class="form-control" id="password" placeholder="••••••••">
                                                <!-- Botón para mostrar/ocultar la contraseña -->
                                                <button class="btn btn-outline-secondary password-toggle" type="button" id="togglePassword" aria-label="Mostrar contraseña">
                                                    <i class="bi bi-eye-slash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <div class="mb-3">
                                            <label for="passwordConfirm" class="form-label"><i class="bi bi-lock-fill"></i> Confirmar contraseña</label>
                                            <div class="input-group">
                                                <input type="password" name="passwordConfirm" class="form-control" id="passwordConfirm" placeholder="••••••••">
                                                <!-- Botón para mostrar/ocultar la contraseña de confirmación -->
                                                <button class="btn btn-outline-secondary password-toggle" type="button" id="togglePasswordConfirm" aria-label="Mostrar confirmar contraseña">
                                                    <i class="bi bi-eye-slash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <figcaption class="blockquote-footer text-dark-emphasis">La contraseña debe tener, al menos, una letra mayúscula, un número y mínimo 8 dígitos.</figcaption>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-golden" id="submitBtn"><i class="bi bi-person-plus"></i> Registrar</button>
                                </div>

                                <div class="text-center mb-0 pt-3">
                                    <span class="form-text">¿Ya estás registrado?</span>
                                    <a href="<?= Paths::to('login') ?>" class="link">Inicia sesión</a>
                                </div>

                            </form>
                        </div>
                    </div>

                    <div class="brand-section">
                        <img src="<?= Paths::asset('img/logo.png') ?>" alt="Logo" width="350" height="350" class="img-fluid rounded-circle register-logo" style="display: block; margin: 0 auto;">
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="<?= Paths::asset('SweetAlert2/sweetalert2.all.min.js') ?>"></script>
    <script src="<?= Paths::asset('js/register.js') ?>"></script>
</body>

</html>