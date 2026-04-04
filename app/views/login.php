<!DOCTYPE html>
<html>
<head>
    <title>Inicio de Sesión</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="<?php echo URL_BASE; ?>assets/Bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo URL_BASE; ?>assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-warning-subtle">

<div class="container mt-3">

    <div class="row justify-content-center">

        <div class="col-md-5">

            <div class="card border-0 shadow p-3 mb-3 bg-body-tertiary rounded">
               
                    <img src="<?php echo URL_BASE; ?>assets/img/logo.png" alt="Logo" width="140" height="150"  class="img-fluid rounded-circle mt-3" style="display: block; margin: 0 auto;">
                    <h1 class="fw-bold text-center text-black pt-3 mb-1">Bienvenido</h1>

                    <div class="card-body p-4">

                            <form method="POST" action="<?php echo URL_BASE; ?>index.php" id="formLogin">

                                <input type="hidden" name="action" value="login">

                                <div class="input-group mb-3">
                                    <span class="input-group-text">Correo electrónico</span>
                                    <input type="text" name="email" class="form-control" id="email">
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text">Contraseña</span>
                                    <input type="password" name="password" class="form-control" id="password">
                                </div>

                                <div class="alert alert-danger text-center mx-auto d-none mb-3" role="alert" id="errorContainer" >
                                </div>

                                <div class="d-grid gap-2 col-6 mx-auto" id="btn-submit">
                                    <input type="submit" class="btn btn-primary" value="Ingresar">
                                </div>

                                <div class="text-center mb-2 pt-3" id="link">
                                    <label class="">¿No estás registrado?</label></br><a href="<?php echo URL_BASE ?>registro">Regístrate aquí</a>
                                </div>

                            </form>

                    </div>


            </div>

        </div>

    </div>

</div>

<script src="<?php echo URL_BASE; ?>assets/SweetAlert2/sweetalert2.all.min.js"></script>
<script src="<?php echo URL_BASE; ?>assets/js/login.js"></script>
</body>
</html>