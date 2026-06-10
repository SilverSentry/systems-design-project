<?php
use App\Core\Paths;
require __DIR__ . '/layouts/head.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 text-center">
            <div class="card shadow-sm border-0">
                <div class="card-body py-5">
                    <h1 class="display-4 text-danger">404</h1>
                    <p class="lead">La página que buscas no existe o no está disponible.</p>
                    <a href="<?= Paths::to('login') ?>" class="mt-3 btn btn-golden btn-golden-all btn-lg">Volver al inicio</a>
                </div>
            </div>
        </div>
    </div>
</div>