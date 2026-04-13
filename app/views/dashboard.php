<?php

//Si la variable de sesión no existe, significa que no ha pasado por el login
if(!isset($_SESSION['user_id'])) {
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
      <img src="<?php echo URL_BASE; ?>assets/img/logo.png" alt="Logo" width="30" height="24" class="d-inline-block rounded-circle align-text-top">
      <span class="navbar-brand">Ordo Stetic</span>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Link</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Dropdown
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">Action</a></li>
              <li><a class="dropdown-item" href="#">Another action</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="#">Something else here</a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link disabled" aria-disabled="true">Disabled</a>
          </li>
        </ul>
        <span class="fw-bold text-center text-white">Bienvenido, <?php echo htmlspecialchars(ucfirst($userName)); ?></span>
        <form class="d-flex" role="search">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" />
          <button class="btn btn-outline-success" type="submit">Search</button>
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

            <form method="post" action="<?php echo URL_BASE; ?>index.php">

              <input type="hidden" name="action" value="logout">

              <div class="d-grid gap-2 col-6 mx-auto">
                <a href="<?php echo URL_BASE; ?>index.php" class="text-center"><button type="submit" class="btn btn-primary">Cerrar Sesión</button></a>
              </div>

            </form>

          </div>

        </div>

      </div>

    </div>

</body>

</html>