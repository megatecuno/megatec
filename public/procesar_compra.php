<?php
header('Content-Type: text/html; charset=utf-8');
require_once dirname(__dir__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'auth.php';
requirelogin();

require_once dirname(__dir__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'header.php';
?>

<h2>procesar compra</h2>
<div style="background:white; padding:2rem; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.1); max-width:800px; margin:2rem auto;">
    <h3>¡gracias por tu compra!</h3>
    <p>estamos procesando tu pedido. recibirás un correo de confirmación en breve.</p>
    <a href="<?= url_base ?>" style="display:inline-block; background:#4db6ac; color:white; padding:0.5rem 1rem; border-radius:4px; text-decoration:none; margin-top:1rem;">
        volver al inicio
    </a>
</div>

<?php
header('Content-Type: text/html; charset=utf-8'); require_once dirname(__dir__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'footer.php'; ?>

