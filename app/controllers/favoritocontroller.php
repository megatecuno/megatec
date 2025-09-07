<?php
header('Content-Type: text/html; charset=utf-8');
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Favorito.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'auth.php';

class FavoritoController {
    private $favoritoModel;

    public function __construct() {
        $this->favoritoModel = new Favorito();
    }

    public function toggle() {
        requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario_id = $_SESSION['user_id'];
            $articulo_id = intval($_POST['articulo_id']);

            if ($this->favoritoModel->existe($usuario_id, $articulo_id)) {
                $this->favoritoModel->eliminar($usuario_id, $articulo_id);
                echo json_encode(['status' => 'removed', 'message' => 'Artículo eliminado de favoritos']);
            } else {
                $this->favoritoModel->agregar($usuario_id, $articulo_id);
                echo json_encode(['status' => 'added', 'message' => 'Artículo agregado a favoritos']);
            }
            exit;
        }
    }

    public function ver() {
        requireLogin();

        $favoritos = $this->favoritoModel->getByUsuario($_SESSION['user_id']);

        require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'header.php';
        ?>

        <h2>Mis Favoritos</h2>
        <?php if (empty($favoritos)): ?>
            <div style="background:white; padding:2rem; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.1); max-width:800px; margin:2rem auto;">
                <p>❤️ No tienes artículos en favoritos.</p>
                <a href="<?= URL_BASE ?>" style="display:inline-block; background:#4DB6AC; color:white; padding:0.5rem 1rem; border-radius:4px; text-decoration:none; margin-top:1rem;">
                    Explorar artículos
                </a>
            </div>
        <?php else: ?>
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap:1rem; margin:2rem 0;">
                <?php foreach ($favoritos as $favorito): ?>
                    <div style="background:white; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.1); padding:1rem;">
                        <?php if (!empty($favorito['ml_item_id'])): ?>
                            <div style="background:#FFD700; padding:0.3rem 0.5rem; border-radius:4px; font-weight:bold; margin-bottom:0.5rem;">📦 Mercado Libre</div>
                        <?php endif; ?>
                        <h3><?= htmlspecialchars($favorito['titulo']) ?></h3>
                        <p style="font-size:1.2rem; font-weight:bold; color:#4DB6AC;">
                            $<?= number_format($favorito['precio'], 2, ',', '.') ?>
                        </p>
                        <p>Stock: <?= $favorito['stock'] ?></p>
                        <div style="display:flex; gap:0.5rem; margin-top:1rem;">
                            <a href="<?= URL_BASE ?>articulo.php?id=<?= $favorito['articulo_id'] ?>" 
                               style="flex:1; background:#4DB6AC; color:white; padding:0.5rem; text-decoration:none; border-radius:4px; text-align:center;">
                                Ver
                            </a>
                            <button class="btn-toggle-favorito" data-id="<?= $favorito['articulo_id'] ?>" 
                                    style="flex:1; background:#E57373; color:white; padding:0.5rem; border:none; border-radius:4px; cursor:pointer;">
                                Eliminar
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'footer.php'; ?>
        <?php
    }
}
?>