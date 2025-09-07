<?php
header('Content-Type: text/html; charset=utf-8');
require_once dirname(__dir__, 3) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once dirname(__dir__, 3) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'auth.php';

requirelogin();

if ($_SESSION['user_rol'] !== 'operario' && $_SESSION['user_rol'] !== 'administrador') {
    header("Location: " . URL_BASE);
    exit;
}

require_once dirname(__dir__, 3) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'header.php';

require_once dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Articulo.php';
$articuloModel = new Articulo();
$articulos = $articuloModel->getAll();
?>

<h2 class="text-2xl font-bold mb-4">Mis Artículos</h2>

<div style="background:white; padding:2rem; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.1); max-width:800px; margin:2rem auto;">
    <table>
        <thead>
            <tr>
                <th>Título</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($articulos as $articulo): ?>
                <tr>
                    <td><?= htmlspecialchars($articulo['titulo']) ?></td>
                    <td>$<?= number_format($articulo['precio'], 2, ',', '.') ?></td>
                    <td><?= $articulo['stock'] ?></td>
                    <td><?= ucfirst($articulo['estado']) ?></td>
                    <td>
                        <a href="<?= URL_BASE ?>articulo.php?id=<?= $articulo['id'] ?>">Ver</a> |
                        <a href="#">Editar</a> |
                        <a href="#">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
require_once dirname(__dir__, 3) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'footer.php'; 
?>
