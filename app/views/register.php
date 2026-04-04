<!DOCTYPE html>
<html>
<head>
    <title>Registro de Usuario</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="<?php echo URL_BASE; ?>assets/Bootstrap/bootstrap.min.css" rel="stylesheet">
  </head>
</head>
<body class="bg-warning-subtle">

<div class="container mt-3">

    <div class="row justify-content-center">

        <div class="col-md-5">

            <div class="card border-0 shadow p-9 mb-2 bg-body-tertiary rounded">

                <img src="<?php echo URL_BASE; ?>assets/img/logo.png" alt="Logo" width="140" height="150"  class="img-fluid rounded-circle mt-3" style="display: block; margin: 0 auto;">

                    <h3 class="fw-bold text-center text-black pt-3 mb-1">Ingrese sus datos</h3>

                    <div class="card-body p-4">

                            <form method="post" action="<?php echo URL_BASE; ?>index.php" id="formRegister">

                                <input type="hidden" name="action" value="register">

                                <div class="input-group mb-3">
                                    <span class="input-group-text">Nombre</span>
                                    <input type="text" name="name" class="form-control" id="name">
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text">Apellido</span>
                                    <input type="text" name="surname" class="form-control" id="surname">
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text">Correo electrónico</span>
                                    <input type="text" name="email" class="form-control" id="email">
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text">Contraseña</span>
                                    <input type="password" name="password" class="form-control" id="password">
                                </div>

                                <figcaption class="blockquote-footer">La contraseña debe tener, al menos, una letra mayúscula, un número y mínimo 8 dígitos.</figcaption>

                                <div class="input-group mb-3">
                                    <span class="input-group-text">Confirmar contraseña</span>
                                    <input type="password" name="passwordConfirm" class="form-control" id="password">
                                </div>

                                <div class="alert alert-danger text-center mx-auto d-none mb-3" role="alert" id="errorContainer">
                                </div>

                                <div class="d-grid gap-2 col-6 mx-auto">
                                    <input type="submit" class="btn btn-primary " value="Registrar">
                                </div>

                                <div class="text-center mb-2 pt-3">
                                    <label class="">¿Ya estás registrado?</label></br><a href="<?php echo URL_BASE ?>login">Inicia sesión</a>
                                </div>

                            </form>

                    </div>


            </div>

        </div>

    </div>

</div>

<script src="<?php echo URL_BASE; ?>assets/SweetAlert2/sweetalert2.all.min.js"></script>
<script src="<?php echo URL_BASE; ?>assets/js/register.js"></script>
</body>
</html>