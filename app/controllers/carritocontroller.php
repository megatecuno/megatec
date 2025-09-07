<?php
header('Content-Type: text/html; charset=utf-8');
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Carrito.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'auth.php';

class CarritoController {
    private $carritoModel;

    public function __construct() {
        $this->carritoModel = new Carrito();
    }

    public function agregar() {
        requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario_id = $_SESSION['user_id'];
            $articulo_id = intval($_POST['articulo_id']);
            $cantidad = intval($_POST['cantidad']) ?: 1;

            $this->carritoModel->agregar($usuario_id, $articulo_id, $cantidad);

            if (isset($_POST['redirect']) && $_POST['redirect'] == '1') {
                header("Location: " . URL_BASE . "dashboard/usuario/carrito.php");
                exit;
            } else {
                echo json_encode(['status' => 'success', 'message' => 'Artículo agregado al carrito']);
                exit;
            }
        }
    }

    public function ver() {
        requireLogin();

        $items = $this->carritoModel->getByUsuario($_SESSION['user_id']);
        $total = $this->carritoModel->getTotal($_SESSION['user_id']);

        require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'header.php';
        ?>

        <h2>Mi Carrito de Compras</h2>
        <?php if (empty($items)): ?>
            <div style="background:white; padding:2rem; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.1); max-width:800px; margin:2rem auto;">
                <p>🛒 Tu carrito está vacío.</p>
                <a href="<?= URL_BASE ?>" style="display:inline-block; background:#4DB6AC; color:white; padding:0.5rem 1rem; border-radius:4px; text-decoration:none; margin-top:1rem;">
                    Seguir comprando
                </a>
            </div>
        <?php else: ?>
            <div style="max-width:800px; margin:2rem auto; background:white; padding:2rem; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.1);">
                <table style="width:100%; border-collapse:collapse; margin-bottom:2rem;">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['titulo']) ?></td>
                                <td>$<?= number_format($item['precio'], 2, ',', '.') ?></td>
                                <td><?= $item['cantidad'] ?></td>
                                <td>$<?= number_format($item['precio'] * $item['cantidad'], 2, ',', '.') ?></td>
                                <td>
                                    <a href="<?= URL_BASE ?>eliminar_del_carrito.php?articulo_id=<?= $item['articulo_id'] ?>" 
                                       style="background:#E57373; color:white; padding:0.5rem; text-decoration:none; border-radius:4px;">
                                        Eliminar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <div style="display:flex; justify-content:space-between; align-items:center; margin-top:2rem; padding-top:1rem; border-top:2px solid #4DB6AC;">
                    <h3>Total: $<?= number_format($total, 2, ',', '.') ?></h3>
                    <div>
                        <a href="<?= URL_BASE ?>procesar_pago.php" 
                           style="background:#4DB6AC; color:white; padding:0.5rem 1rem; text-decoration:none; border-radius:4px;">
                            Procesar Pago
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'footer.php'; ?>
        <?php
    }

    public function eliminar() {
        requireLogin();

        $usuario_id = $_SESSION['user_id'];
        $articulo_id = intval($_GET['articulo_id']);

        $this->carritoModel->eliminar($usuario_id, $articulo_id);
        header("Location: " . URL_BASE . "dashboard/usuario/carrito.php");
        exit;
    }
}
?>