<?php

//Si la variable de sesión no existe, significa que no ha pasado por el login
if (!isset($_SESSION['user_id'])) {
  redirect('login');
  exit();
}

$userName = $_SESSION['name'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="<?php echo URL_BASE; ?>assets/Bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <title>Welcome</title>
</head>

<body>

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
    <div class="container-fluid">
      <img src="<?php echo URL_BASE; ?>assets/img/logo.png" alt="Logo" width="50" height="40" class="d-inline-block rounded-circle align-text-top">
      <span class="navbar-brand">STUDIO ORDO STETIC</span>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        </ul>
        <span class="fw-bold text-center text-white">Bienvenido, <?php echo htmlspecialchars(ucfirst($userName)); ?></span>
        <form method="post" action="<?php echo URL_BASE; ?>index.php">

              <input type="hidden" name="action" value="logout">
                <a href="<?php echo URL_BASE; ?>index.php" class="text-center"><button type="submit" class="btn btn-primary">Cerrar Sesión</button></a>
              

            </form>
      </div>
    </div>
  </nav>

  <div class="container">

    <div class="row">

      <div class="col">

        <div class="card border-0 shadow-lg">
          <div class="card-body p-2">

            <div class="card-header bg-success text-white text-center py-3 mb-5">
              <h1 class="fw-bold text-center pt-5 mb-5">Bienvenido, <?php echo htmlspecialchars($userName); ?></h1>
            </div>

          </div>

        </div>

      </div>

    </div>

</body>

</html>