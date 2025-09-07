<?php
header('Content-Type: text/html; charset=utf-8');
require_once dirname(__dir__, 3) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once dirname(__dir__, 3) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'auth.php';

requirelogin();

if (<?php
header('Content-Type: text/html; charset=utf-8');
require_once dirname(__dir__, 3) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once dirname(__dir__, 3) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'auth.php';

requirelogin();

if ($_session['user_rol'] !== 'usuario') {
    header("location: " . url_base);
    exit;
}

require_once dirname(__dir__, 3) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'header.php';
?>

<h2>panel de usuario</h2>
<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:1rem; margin:2rem 0;">
    <a href="<?= url_base ?>dashboard/usuario/perfil.php" style="background:#4db6ac; color:white; padding:1rem; border-radius:8px; text-align:center; text-decoration:none;">
        ?? mi perfil
    </a>
    <a href="<?= url_base ?>dashboard/usuario/carrito.php" style="background:#4db6ac; color:white; padding:1rem; border-radius:8px; text-align:center; text-decoration:none;">
        ?? mi carrito
    </a>
    <a href="<?= url_base ?>dashboard/usuario/favoritos.php" style="background:#4db6ac; color:white; padding:1rem; border-radius:8px; text-align:center; text-decoration:none;">
        ?? mis favoritos
    </a>
</div>

<?php
header('Content-Type: text/html; charset=utf-8'); require_once dirname(__dir__, 3) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'footer.php'; ?>

SESSION['user_rol'] !== 'usuario') {
    header("location: " . url_base);
    exit;
}

require_once dirname(__dir__, 3) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'header.php';
?>

<h2>panel de usuario</h2>
<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:1rem; margin:2rem 0;">
    <a href="<?= url_base ?>dashboard/usuario/perfil.php" style="background:#4db6ac; color:white; padding:1rem; border-radius:8px; text-align:center; text-decoration:none;">
        ?? mi perfil
    </a>
    <a href="<?= url_base ?>dashboard/usuario/carrito.php" style="background:#4db6ac; color:white; padding:1rem; border-radius:8px; text-align:center; text-decoration:none;">
        ?? mi carrito
    </a>
    <a href="<?= url_base ?>dashboard/usuario/favoritos.php" style="background:#4db6ac; color:white; padding:1rem; border-radius:8px; text-align:center; text-decoration:none;">
        ?? mis favoritos
    </a>
</div>

<?php
header('Content-Type: text/html; charset=utf-8'); require_once dirname(__dir__, 3) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'footer.php'; ?>

