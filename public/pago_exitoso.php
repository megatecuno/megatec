<?php
header('Content-Type: text/html; charset=utf-8');
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'auth.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Carrito.php';

requirelogin();

$carritoModel = new Carrito();
$carritoModel->vaciarCarrito($_SESSION['user_id']);

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'header.php';
?>

<h2 class="text-2xl font-bold mb-4">¡Pago Exitoso!</h2>
<div style="background:white; padding:2rem; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.1); max-width:600px; margin:2rem auto;">
    <p>Tu compra ha sido procesada exitosamente. ¡Gracias por tu pedido!</p>
    <a href="<?= URL_BASE ?>" style="display:inline-block; background:#4DB6AC; color:white; padding:0.5rem 1rem; border-radius:4px; text-decoration:none; margin-top:1rem;">
        Volver al inicio
    </a>
</div>

<?php require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'footer.php'; ?>