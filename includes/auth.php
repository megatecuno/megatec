<?php
header('Content-Type: text/html; charset=utf-8');
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';

session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUserRole() {
    return $_SESSION['user_rol'] ?? null;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: " . URL_BASE . "login.php");
        exit;
    }
}

function redirectByRole() {
    $role = getUserRole();
    if ($role === 'administrador') {
        header("Location: " . URL_BASE . "dashboard/administrador/index.php");
        exit;
    } elseif ($role === 'operario') {
        header("Location: " . URL_BASE . "dashboard/operario/index.php");
        exit;
    } elseif ($role === 'usuario') {
        header("Location: " . URL_BASE . "dashboard/usuario/index.php");
        exit;
    } else {
        header("Location: " . URL_BASE);
        exit;
    }
}
?>