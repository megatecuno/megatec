<?php
header('Content-Type: text/html; charset=utf-8');
require_once dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'header.php';
?>

<h2>Iniciar Sesión</h2>
<div style="background:white; padding:2rem; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.1); max-width:400px; margin:2rem auto;">
    <form method="POST">
        <div style="margin-bottom:1rem;">
            <label>Email:</label><br>
            <input type="email" name="email" required style="padding:0.5rem; width:100%;">
        </div>
        <div style="margin-bottom:1rem;">
            <label>Contraseña:</label><br>
            <input type="password" name="password" required style="padding:0.5rem; width:100%;">
        </div>
        <button type="submit" style="background:#4DB6AC; color:white; padding:0.5rem 1rem; border:none; border-radius:4px; cursor:pointer; width:100%;">
            Entrar
        </button>
    </form>
    <p style="text-align:center; margin-top:1rem;">
        ¿No tienes cuenta? <a href="<?= URL_BASE ?>register.php" style="color:#4DB6AC;">Regístrate aquí</a>
    </p>
</div>

<?php require_once dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'footer.php'; ?>