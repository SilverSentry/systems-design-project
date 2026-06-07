<?php
//Layout compartido para la etiqueta <head> y apertura de <body>
//Se definen variables opcionales: $title y $bodyClass

use App\Core\Paths;

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Studio Ordo Stetic' ?></title>
    <link href="<?= Paths::asset('Bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= Paths::asset('Bootstrap-icons/bootstrap-icons.min.css') ?>" rel="stylesheet">
    <link href="<?= Paths::asset('DataTables/dataTables.bootstrap5.min.css') ?>" rel="stylesheet">
    <link rel="icon" type="image/png" href="<?= Paths::asset('img/logo.png'); ?>">
    <link href="<?= Paths::asset('css/styles.css') ?>" rel="stylesheet">
</head>

<body class="<?= $bodyClass ?? '' ?>">
