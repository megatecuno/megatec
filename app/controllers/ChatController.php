<?php
header('Content-Type: text/html; charset=utf-8');
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Chat.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Articulo.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'auth.php';

class ChatController {
    private $chatModel;
    private $articuloModel;

    public function __construct() {
        $this->chatModel = new Chat();
        $this->articuloModel = new Articulo();
    }

    public function ver($articulo_id) {
        requireLogin();

        $articulo = $this->articuloModel->getById($articulo_id);
        if (!$articulo) {
            header("Location: " . URL_BASE);
            exit;
        }

        $mensajes = $this->chatModel->getMessages($articulo_id, $_SESSION['user_id']);

        require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'header.php';
        ?>

        <h2>Chat - <?= htmlspecialchars($articulo['titulo']) ?></h2>
        <div style="background:white; padding:2rem; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.1); max-width:800px; margin:2rem auto;">
            <div style="height:300px; overflow-y:auto; border:1px solid #ddd; padding:1rem; margin-bottom:1rem;">
                <?php foreach ($mensajes as $mensaje): ?>
                    <div style="margin-bottom:0.5rem; padding:0.5rem; background:<?= $mensaje['remitente_id'] == $_SESSION['user_id'] ? '#e3f2fd' : '#f1f8e9' ?>; border-radius:4px;">
                        <strong><?= htmlspecialchars($mensaje['remitente_nombre']) ?>:</strong>
                        <p><?= htmlspecialchars($mensaje['mensaje']) ?></p>
                        <small><?= $mensaje['fecha_envio'] ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
            <form method="POST" action="<?= URL_BASE ?>enviar_mensaje.php?articulo_id=<?= $articulo_id ?>">
                <div style="margin-bottom:1rem;">
                    <textarea name="mensaje" required style="padding:0.5rem; width:100%; height:60px;" placeholder="Escribe tu mensaje..."></textarea>
                </div>
                <button type="submit" style="background:#4DB6AC; color:white; padding:0.5rem 1rem; border:none; border-radius:4px; cursor:pointer;">
                    Enviar Mensaje
                </button>
            </form>
        </div>

        <?php require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'footer.php'; ?>
        <?php
    }

    public function enviar() {
        requireLogin();

        $articulo_id = intval($_GET['articulo_id']);
        $mensaje = $_POST['mensaje'];

        $articulo = $this->articuloModel->getById($articulo_id);
        if (!$articulo) {
            header("Location: " . URL_BASE);
            exit;
        }

        $this->chatModel->sendMessage($_SESSION['user_id'], $articulo['creador_id'], $articulo_id, $mensaje);

        header("Location: " . URL_BASE . "ver_chat.php?articulo_id=" . $articulo_id);
        exit;
    }
}
?>