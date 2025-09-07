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

if ($_session['user_rol'] !== 'administrador') {
    header("location: " . url_base);
    exit;
}

require_once dirname(__dir__, 3) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'header.php';
?>

<h2>gestión de usuarios</h2>
<div style="background:white; padding:2rem; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.1); max-width:800px; margin:2rem auto;">
    <p>?? aquí gestionarás usuarios cuando implementemos el crud.</p>
    <a href="<?= url_base ?>dashboard/administrador/index.php" style="display:inline-block; background:#4db6ac; color:white; padding:0.5rem 1rem; border-radius:4px; text-decoration:none; margin-top:1rem;">
        volver al panel
    </a>
</div>

<?php
header('Content-Type: text/html; charset=utf-8'); require_once dirname(__dir__, 3) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'footer.php'; ?>

SESSION['user_rol'] !== 'administrador') {
    header("location: " . url_base);
    exit;
}

require_once dirname(__dir__, 3) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'header.php';
?>

<h2>gestión de usuarios</h2>
<div style="background:white; padding:2rem; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.1); max-width:800px; margin:2rem auto;">
    <p>?? aquí gestionarás usuarios cuando implementemos el crud.</p>
    <a href="<?= url_base ?>dashboard/administrador/index.php" style="display:inline-block; background:#4db6ac; color:white; padding:0.5rem 1rem; border-radius:4px; text-decoration:none; margin-top:1rem;">
        volver al panel
    </a>
</div>

<?php
header('Content-Type: text/html; charset=utf-8'); require_once dirname(__dir__, 3) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'footer.php'; ?>

