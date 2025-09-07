<?php
header('Content-Type: text/html; charset=utf-8');
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'auth.php';
requireLogin();

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'header.php';
?>

<h2>Procesar Pago - Mercado Pago</h2>
<div style="background:white; padding:2rem; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.1); max-width:800px; margin:2rem auto; text-align:center;">
    <img src="https://http2.mlstatic.com/frontend-assets/ui-navigation/5.15.1/mercadopago-logo-large.png" alt="Mercado Pago" style="height:60px; margin-bottom:2rem;">
    <h3>Simulación de pago con Mercado Pago</h3>
    <p>En un entorno real, aquí se integraría con la API de Mercado Pago.</p>
    <p>Por ahora, simulamos que el pago fue exitoso.</p>
    <a href="<?= URL_BASE ?>pago_exitoso.php" 
       style="background:#4DB6AC; color:white; padding:0.5rem 1rem; text-decoration:none; border-radius:4px; margin-top:1rem; display:inline-block;">
        Simular Pago Exitoso
    </a>
</div>

<?php require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'footer.php'; ?>