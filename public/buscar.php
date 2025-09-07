<?php
header('Content-Type: text/html; charset=utf-8');
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'db.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'auth.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Articulo.php';

$articuloModel = new Articulo();
$query = $_GET['q'] ?? '';
$articulos = [];

if (!empty($query)) {
    $articulos = $articuloModel->search($query);
}

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'header.php';
?>

<h2 class="text-2xl font-bold mb-4">Resultados de búsqueda para "<?= htmlspecialchars($query) ?>"</h2>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <?php if (empty($articulos)): ?>
        <p>No se encontraron artículos para tu búsqueda.</p>
    <?php else: ?>
        <?php foreach ($articulos as $articulo): ?>
            <div class="product-card-container">
                <div class="image-placeholder-replica">
                    <a href="<?= URL_BASE ?>articulo.php?id=<?= $articulo['id'] ?>">
                        <?php
                        $imagenes = $articuloModel->getImagenes($articulo['id']);
                        if (!empty($imagenes)): ?>
                            <img src="<?= URL_BASE ?>uploads/articulos/<?= $imagenes[0]['ruta_imagen'] ?>" alt="<?= htmlspecialchars($articulo['titulo']) ?>" style="width:100%; height:100%; object-fit:cover;">
                        <?php else: ?>
                            <span>Imagen</span>
                        <?php endif; ?>
                    </a>
                </div>
                <div class="content-container">
                    <a href="<?= URL_BASE ?>articulo.php?id=<?= $articulo['id'] ?>" class="product-title"><?= htmlspecialchars($articulo['titulo']) ?></a>
                    <div class="price-container">
                        <span class="current-price">$ <?= number_format($articulo['precio'], 2, ',', '.') ?></span>
                    </div>
                    <?php if ($articulo['envio_gratis']): ?>
                        <span class="shipping">Envío gratis</span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'footer.php'; ?>