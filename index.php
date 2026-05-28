<?php

//1. Cargar el autoloader de Composer
require_once __DIR__ . '/vendor/autoload.php';

//2. Cargar las variables del archivo .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

require_once 'config/config.php';
require_once 'routes/web.php';

?>