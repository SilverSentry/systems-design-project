<?php

require_once 'core/router.php';

$router = new router();

$router->add('login', 'app/views/login.php');
$router->add('register', 'app/views/register.php');
$router->add('AdminDashboard', 'app/views/admin_dashboard.php');

$router->run(isset($_GET['p']) ? $_GET['p'] : 'login');

?>