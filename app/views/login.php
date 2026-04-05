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

        <div class="col-md-5">

            <div class="card border-0 shadow-lg p-3 bg-white rounded-4">

                <div class="text-center mt-1">  
                    <img src="<?php echo URL_BASE; ?>assets/img/logo.png" alt="Logo" width="140" height="150"  class="img-fluid rounded-circle mt-3" style="display: block; margin: 0 auto;">
                    <h1 class="fw-bold text-center text-black pt-3 mb-1">Iniciar Sesión</h1>
                </div>

                    <div class="card-body p-4">

                        <div class="alert alert-danger text-center mx-auto d-none mb-3" role="alert" id="errorContainer">
                        </div>

                            <form method="POST" action="<?php echo URL_BASE; ?>index.php" id="formLogin">

                                <input type="hidden" name="action" value="login">

                                <div class="form-floating mb-3">
                                    <input type="text" name="email" class="form-control" id="email" placeholder="email">
                                    <label for="email">Correo electrónico</label>
                                </div>

                                <div class="form-floating mb-3">                              
                                    <input type="password" name="password" class="form-control" id="password" placeholder="password">
                                    <label for="password">Contraseña</label>
                                </div>                                

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn-golden btn">Ingresar</button>
                                </div>

                                <div class="text-center mb-2 pt-3" id="link">
                                    <label class="">¿No estás registrado?</label></br>
                                    <a href="<?php echo URL_BASE ?>registro" class="link">Regístrate aquí</a>
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