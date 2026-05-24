<?php
// Layout compartido para cierre de <body> y <html>
// También carga scripts comunes para todas las vistas.
?>
    <script src="<?= Paths::asset('SweetAlert2/sweetalert2.all.min.js') ?>"></script>
    <?php if(!empty($extraScripts) && is_array($extraScripts)): ?>
        <?php foreach($extraScripts as $script): ?>
            <script src="<?= Paths::asset($script) ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    <script>
  $(document).ready( function () {
        $('#example').DataTable();
    });
</script>
</body>

</html>
