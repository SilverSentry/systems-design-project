<!DOCTYPE html>
<html>

<head>
    <title>Registro de Usuario</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="<?php echo URL_BASE; ?>assets/Bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo URL_BASE; ?>assets/css/style.css" rel="stylesheet">
    <link href="<?php echo URL_BASE; ?>assets/Bootstrap-icons/bootstrap-icons.min.css" rel="stylesheet">
</head>
</head>

<body class="d-flex align-items-center min-vh-100">

    <div class="container mt-1">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="split-container-register">

                    <div class="register-section">
                        <div class="text-center mt-1">
                            <!-- <img src="<php echo URL_BASE; ?>assets/img/logo.png" alt="Logo" width="140" height="150"  class="img-fluid rounded-circle mt-3" style="display: block; margin: 0 auto;"> -->
                            <h1 class="fw-bold text-center text-black pt-1 mb-1">Registro</h1>
                        </div>



                        <div class="card-body p-4">

                            <div class="alert alert-danger text-center mx-auto invisible mb-2" role="alert" id="errorContainer" style="min-height: 58px;">
                            </div>

                            <form method="post" action="<?php echo URL_BASE; ?>index.php" id="formRegister">

                                <input type="hidden" name="action" value="register">

                                <!-- input nombre y apellido -->
                                <div class="row g-2">
                                    <div class="col-md">
                                        <div class="mb-3">
                                            <label for="name" class="form-label"><i class="bi bi-person"></i> Nombre</label>
                                            <input type="text" name="name" class="form-control" id="name" placeholder="">
                                        </div>
                                    </div>

                                    <div class="col-md">
                                        <div class="mb-3">
                                            <label for="surname" class="form-label"><i class="bi bi-person-fill"></i> Apellido</label>
                                            <input type="text" name="surname" class="form-control" id="surname" placeholder="">
                                        </div>
                                    </div>
                                </div>

                                <!-- input correo -->
                                <div class="mb-3">
                                    <label for="email" class="form-label"><i class="bi bi-envelope-fill"></i> Correo electrónico</label>
                                    <input type="text" name="email" class="form-control" id="email" placeholder="@example.com">
                                </div>

                                <!-- input contraseña y confirmar contraseña -->
                                <div class="row g-2 mb-2">
                                    <div class="col-md">
                                        <div class="mb-3">
                                            <label for="password" class="form-label"><i class="bi bi-lock"></i> Contraseña</label>
                                            <input type="password" name="password" class="form-control" id="password" placeholder="********">
                                        </div>
                                    </div>

                                    <div class="col-md">
                                        <div class="mb-3">
                                            <label for="password" class="form-label"><i class="bi bi-lock-fill"></i> Confirmar contraseña</label>
                                            <input type="password" name="passwordConfirm" class="form-control" id="passwordConfirm" placeholder="********">
                                        </div>
                                    </div>
                                </div>

                                <figcaption class="blockquote-footer text-dark-emphasis">La contraseña debe tener, al menos, una letra mayúscula, un número y mínimo 8 dígitos.</figcaption>


                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-golden">Registrar</button>
                                </div>

                                <div class="text-center mb-0 pt-3">
                                    <label class="">¿Ya estás registrado?</label></br>
                                    <a href="<?php echo URL_BASE ?>login" class="link">Inicia sesión</a>
                                </div>

                            </form>
                        </div>
                    </div>

                    <div class="brand-section-register">
                        <img src="<?php echo URL_BASE; ?>assets/img/logo.png" alt="Logo" width="180" height="150" class="img-fluid rounded-circle register-logo" style="display: block; margin: 0 auto;">
                        <h1 class="fw-bold welcome"></h1>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo URL_BASE; ?>assets/SweetAlert2/sweetalert2.all.min.js"></script>
    <script src="<?php echo URL_BASE; ?>assets/js/register.js"></script>
</body>

</html>