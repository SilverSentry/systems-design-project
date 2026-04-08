<!DOCTYPE html>
<html>

<head>
    <title>Inicio de Sesión</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="<?php echo URL_BASE; ?>assets/Bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo URL_BASE; ?>assets/css/style.css" rel="stylesheet">
</head>

<body class="d-flex align-items-center min-vh-100">

    <div class="container mt-3">

        <div class="row justify-content-center">

            <div class="col-lg-10">

                <div class="split-container">

                    <div class="login-section">
                        <div class="text-center mt-1">
                            <!-- <img src="<php echo URL_BASE; ?>assets/img/logo.png" alt="Logo" width="140" height="150"  class="img-fluid rounded-circle mt-3" style="display: block; margin: 0 auto;"> -->
                            <h1 class="fw-bold text-center text-black pt-1 mb-1">Iniciar Sesión</h1>
                        </div>

                        <div class="card-body p-4">

                            <div class="alert alert-danger text-center mx-auto invisible mb-2" role="alert" id="errorContainer" style="min-height: 58px;">
                            </div>

                            <form method="POST" action="<?php echo URL_BASE; ?>index.php" id="formLogin">

                                <input type="hidden" name="action" value="login">

                                <div class="mb-3">
                                    <label for="email">Correo electrónico</label>
                                    <input type="text" name="email" class="form-control" id="email">
                                </div>

                                <div class="mb-3">
                                    <label for="password">Contraseña</label>
                                    <input type="password" name="password" class="form-control" id="password">
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn-golden btn">Ingresar</button>
                                </div>

                                <div class="text-center mb-2 pt-3" id="link">
                                    <label class="">¿No estás registrado?</label></br>
                                    <a href="<?php echo URL_BASE ?>register" class="link">Regístrate aquí</a>
                                </div>

                            </form>

                        </div>
                    </div>

                    <div class="brand-section">
                        <img src="<?php echo URL_BASE; ?>assets/img/logo.png" alt="Logo" width="140" height="150" class="img-fluid rounded-circle login-logo" style="display: block; margin: 0 auto;">
                        <h1 class="fw-bold welcome">Bienvenido</h1>
                    </div>


                </div>

            </div>

        </div>

    </div>

    <script src="<?php echo URL_BASE; ?>assets/SweetAlert2/sweetalert2.all.min.js"></script>
    <script src="<?php echo URL_BASE; ?>assets/js/login.js"></script>
</body>

</html>