<?php
header('Content-Type: text/html; charset=utf-8');
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'auth.php';
requireLogin();

// Simular vaciar carrito
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Carrito.php';
$carritoModel = new Carrito();
// En producción, implementar método para vaciar carrito del usuario

$_SESSION['mensaje'] = "¡Pago realizado con éxito! Gracias por tu compra.";

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'header.php';
?>

<h2>¡Pago Exitoso!</h2>
<div style="background:white; padding:2rem; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.1); max-width:800px; margin:2rem auto; text-align:center;">
    <h3>¡Gracias por tu compra!</h3>
    <p>Recibirás un correo de confirmación con los detalles de tu pedido.</p>
    <a href="<?= URL_BASE ?>" 
       style="background:#4DB6AC; color:white; padding:0.5rem 1rem; text-decoration:none; border-radius:4px; margin-top:1rem; display:inline-block;">
        Volver al inicio
    </a>
</div>

<?php require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'footer.php'; ?>