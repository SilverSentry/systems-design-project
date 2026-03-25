<!DOCTYPE html>
<html>
<head>
    <title>Inicio de Sesión</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="<?php echo URL_BASE; ?>assets/css/bootstrap.min.css" rel="stylesheet">
  </head>
</head>
<body>

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-5">

            <div class="card border-0 shadow-lg">

                <div class="card-header bg-dark text-white text-center py-3">
                    <h3 class="fw-bold text-center pt-1 mb-1">Bienvenido</h3>
                </div>

                    <div class="card-body p-4">

                        <form method="post" action="<?php echo URL_BASE; ?>index.php">

                            <input type="hidden" name="action" value="login">

                            <div class="mb-3">
                                <label for="email" class="form-label">Correo electrónico</label>
                                <input type="email" name="email" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Contraseña</label>
                                <input type="password" name="password" class="form-control">
                            </div>

                            <div class="d-grid gap-2 col-6 mx-auto">
                                <input type="submit" class="btn btn-primary " value="Ingresar">
                            </div>

                            <div class="text-center mb-2 pt-3">
                                <label class="">¿No estás registrado?</label><a href="index.php?p=registro">Regístrate aquí</a>
                            </div>

                        </form>

                    </div>


            </div>

        </div>

    </div>

</div>

</body>
</html>