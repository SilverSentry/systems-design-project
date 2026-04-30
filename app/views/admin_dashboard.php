<?php

//Si el usuario no ha iniciado sesión, se redirige al login
if(!SessionManager::isLogged()){
  redirect('login');
}

$user = SessionManager::getUser(); //Obtenemos los datos del usuario logueado

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="<?= paths::asset('Bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
  <link href="<?= paths::asset('css/dashboard.css') ?>" rel="stylesheet">
  <link href="<?= paths::asset('Bootstrap-icons/bootstrap-icons.css') ?>" rel="stylesheet">
  <title>Welcome</title>
</head>

<body>

  <div class="d-flex" id="wrapper">

    <!-- SIDEBAR -->
    <nav id="sidebar" class="bg-dark border-end border-secondary">
      <div class="sidebar-header p-4 text-center">
        <img src="assets/img/logo.png" class="img-fluid rounded-circle" width="80" alt="Logo">
        <h5 class="mt-3 text-gold fw-bold">Studio Ordo</h5>
      </div>

      <ul class="list-unstyled components px-3">
        <li class="active">
          <a href="#" class="nav-link"><i class="bi bi-house-door me-2"></i> Inicio</a>
        </li>
        <li>
          <a href="#" class="nav-link"><i class="bi bi-calendar-event me-2"></i> Citas</a>
        </li>
        <li>
          <a href="#" class="nav-link"><i class="bi bi-people me-2"></i> Clientes</a>
        </li>
        <li>
          <a href="#" class="nav-link"><i class="bi bi-box-seam me-2"></i> Inventario</a>
        </li>
        <hr class="text-secondary">
        <form method="post" action="index.php">
          <input type="hidden" name="action" value="logout">
          <button type="submit" class="btn btn-danger"><i class="bi bi-box-arrow-right"></i> Cerrar Sesión</button>
        </form>
      </ul>
    </nav>

    <div id="page-content-wrapper" class="w-100">

      <div class="container-fluid p-4 p-lg-5 mb-3" style="background: black">
        <h2 class="fw-bold text-white text-center">Bienvenido, <?php echo ucfirst($user['name']); ?></h2>
      </div>

      <section class="py-5">
        <div class="container">
          <div class="row row-cols-1 row-cols-md-3 g-4">

            <div class="col">
              <div class="card h-100 shadow-sm text-center text-black" id="card" style="width: 18rem;">
                <div class="card-body">
                  <h5 class="card-title">Special title treatment</h5>
                  <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                </div>
              </div>
            </div>

            <div class="col">
              <div class="card h-100 shadow-sm  text-black" id="card" style="width: 18rem;">
                <div class="card-body">
                  <h5 class="card-title">Special title treatment</h5>
                  <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                </div>
              </div>
            </div>

            <div class="col">
              <div class="card h-100 shadow-sm  text-black" id="card" style="width: 18rem;">
                <div class="card-body">
                  <h5 class="card-title">Special title treatment</h5>
                  <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                </div>
              </div>
            </div>

          </div>
        </div>
      </section>

    </div>

  </div>
  </div>
  </div>

</body>

</html>