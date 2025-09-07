<?php
header('Content-Type: text/html; charset=utf-8');
require_once dirname(__dir__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'mercadolibrecontroller.php';
$controller = new mercadolibrecontroller();
$controller->login();
?>

