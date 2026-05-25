<?php
// Layout compartido para cierre de <body> y <html>
// También carga scripts comunes para todas las vistas.
?>
<script src="<?= Paths::asset('SweetAlert2/sweetalert2.all.min.js') ?>"></script>
<?php if (!empty($extraScripts) && is_array($extraScripts)): ?>
    <?php foreach ($extraScripts as $script): ?>
        <script src="<?= Paths::asset($script) ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

<footer class="py-4 mt-5">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-12 col-md-auto text-center text-md-start mb-2 mb-md-0">
                <span class="text-secondary fw-semibold">
                    <span class="text-dark">&copy; 2026 STUDIO ORDO STETIC</span>
                </span>
            </div>
        </div>
    </div>
</footer>
</body>
</html>