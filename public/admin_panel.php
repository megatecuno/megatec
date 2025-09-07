<?php
header('Content-Type: text/html; charset=utf-8');
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'auth.php';

requirelogin();

if ($_SESSION['user_rol'] !== 'administrador') {
    header("location: " . URL_BASE);
    exit;
}

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'header.php';
?>

<h2 class="text-2xl font-bold mb-4">Nuevo Panel de Administrador</h2>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <a href="<?= URL_BASE ?>dashboard/administrador/usuarios.php" style="background:#4db6ac; color:white; padding:1rem; border-radius:8px; text-align:center; text-decoration:none;">
        ?? Gestionar Usuarios
    </a>
    <a href="<?= URL_BASE ?>dashboard/administrador/operarios.php" style="background:#4db6ac; color:white; padding:1rem; border-radius:8px; text-align:center; text-decoration:none;">
        ?? Gestionar Operarios
    </a>
    <a href="<?= URL_BASE ?>dashboard/administrador/ml_articulos.php" style="background:#4db6ac; color:white; padding:1rem; border-radius:8px; text-align:center; text-decoration:none;">
        ?? ML Artículos
    </a>
    <a href="<?= URL_BASE ?>dashboard/administrador/perfil.php" style="background:#4db6ac; color:white; padding:1rem; border-radius:8px; text-align:center; text-decoration:none;">
        ?? Mi Perfil
    </a>
</div>

<?php
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'footer.php'; 
?>