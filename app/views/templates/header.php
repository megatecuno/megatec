<?php
header('Content-Type: text/html; charset=utf-8');
$basePath = dirname(__DIR__, 3);
require_once $basePath . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'navbar.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>MegaTec</title>
    <link rel="stylesheet" href="<?= URL_BASE ?>assets/css/style.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body>
    <div style="max-width:1200px; margin:2rem auto; padding:0 1rem; padding-top:4rem;">
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div style="background:#4DB6AC; color:white; padding:1rem; margin:1rem 0; border-radius:4px;">
                <?= htmlspecialchars($_SESSION['mensaje']) ?>
            </div>
            <?php unset($_SESSION['mensaje']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div style="background:#E57373; color:white; padding:1rem; margin:1rem 0; border-radius:4px;">
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>