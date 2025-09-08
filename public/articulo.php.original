<?php
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'ArticuloController.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: " . URL_BASE);
    exit;
}

$controller = new ArticuloController();
$controller->ver($id);
?>