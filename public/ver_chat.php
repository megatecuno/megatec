<?php
header('Content-Type: text/html; charset=utf-8');
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'ChatController.php';
$controller = new ChatController();
$controller->ver($_GET['articulo_id'] ?? 0);
?>