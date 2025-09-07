<?php
header('Content-Type: text/html; charset=utf-8');
require_once dirname(__dir__, 3) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once dirname(__dir__, 3) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'auth.php';

requirelogin();

if ($_SESSION['user_rol'] !== 'administrador') {
    header("location: " . URL_BASE);
    exit;
}

require_once dirname(__dir__, 3) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'mercadolibrecontroller.php';
$controller = new MercadolibreController();
$controller->listarArticulosML();
?>