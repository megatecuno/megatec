<?php
header('Content-Type: text/html; charset=utf-8');
require_once dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'AuthController.php';
$controller = new AuthController();
$controller->perfil();
?>