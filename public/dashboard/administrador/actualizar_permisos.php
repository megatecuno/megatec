<?php
require_once dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'OperarioController.php';
$controller = new OperarioController();
$controller->actualizar();
?>