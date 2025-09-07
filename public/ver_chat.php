<?php
header('Content-Type: text/html; charset=utf-8');
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'auth.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Chat.php';

requirelogin();

$chat_id = $_GET['chat_id'] ?? null;

if (!$chat_id) {
    header("Location: " . URL_BASE . "chat.php");
    exit;
}

$chatModel = new Chat();
$mensajes = $chatModel->getMensajesByChatId($chat_id);
$chatInfo = $chatModel->getChatInfo($chat_id);

if (!$chatInfo || ($chatInfo['remitente_id'] !== $_SESSION['user_id'] && $chatInfo['destinatario_id'] !== $_SESSION['user_id'])) {
    header("Location: " . URL_BASE . "chat.php");
    exit;
}

// Marcar mensajes como leídos
$chatModel->markAsRead($chat_id, $_SESSION['user_id']);

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'header.php';
?>

<h2 class="text-2xl font-bold mb-4">Chat sobre <?= htmlspecialchars($chatInfo['articulo_titulo']) ?></h2>
<div style="background:white; padding:2rem; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.1); max-width:800px; margin:2rem auto;">
    <div style="max-height:400px; overflow-y:auto; border:1px solid #eee; padding:1rem; margin-bottom:1rem;">
        <?php foreach ($mensajes as $mensaje): ?>
            <div style="margin-bottom:0.5rem; <?= $mensaje['remitente_id'] === $_SESSION['user_id'] ? 'text-align:right;' : 'text-align:left;' ?>">
                <span style="font-weight:bold;"><?= htmlspecialchars($mensaje['remitente_nombre']) ?>:</span>
                <span><?= htmlspecialchars($mensaje['mensaje']) ?></span>
                <span style="font-size:0.8rem; color:#999;"><?= $mensaje['fecha_envio'] ?></span>
            </div>
        <?php endforeach; ?>
    </div>
    <form action="<?= URL_BASE ?>enviar_mensaje.php" method="POST">
        <input type="hidden" name="chat_id" value="<?= $chat_id ?>">
        <input type="hidden" name="remitente_id" value="<?= $_SESSION['user_id'] ?>">
        <input type="hidden" name="destinatario_id" value="<?= $chatInfo['remitente_id'] === $_SESSION['user_id'] ? $chatInfo['destinatario_id'] : $chatInfo['remitente_id'] ?>">
        <input type="hidden" name="articulo_id" value="<?= $chatInfo['articulo_id'] ?>">
        <textarea name="mensaje" placeholder="Escribe tu mensaje..." style="width:100%; padding:0.5rem; border:1px solid #ccc; border-radius:4px; margin-bottom:0.5rem;"></textarea>
        <button type="submit" style="background:#4DB6AC; color:white; padding:0.5rem 1rem; border:none; border-radius:4px; cursor:pointer;">Enviar</button>
    </form>
</div>

<?php require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'footer.php'; ?>