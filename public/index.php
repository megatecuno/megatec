<?php
header('Content-Type: text/html; charset=utf-8');
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'db.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'auth.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Articulo.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Favorito.php';

$articuloModel = new Articulo();
$favoritoModel = new Favorito();
$articulos = $articuloModel->getAll('activo');

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'header.php';
?>

<!-- Contenido principal del cuerpo con paneles -->
<div class="main-content">
    <div class="panel">
        <div class="panels-container">
            <h2 class="text-2xl font-bold mb-4 text-center">Artículos Destacados</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <?php foreach (array_slice($articulos, 0, 4) as $articulo): ?>
                    <div class="product-card-container">
                        <div class="image-placeholder-replica">
                            <a href="<?= URL_BASE ?>articulo.php?id=<?= $articulo['id'] ?>">
                                <?php
                                $imagenes = $articuloModel->getImagenes($articulo['id']);
                                if (!empty($imagenes)): ?>
                                    <img src="<?= URL_BASE ?>uploads/articulos/<?= $imagenes[0]['ruta_imagen'] ?>"
                                        alt="<?= htmlspecialchars($articulo['titulo']) ?>"
                                        style="width:100%; height:100%; object-fit:cover;">
                                <?php else: ?>
                                    <span>Imagen</span>
                                <?php endif; ?>
                            </a>
                        </div>
                        <div class="content-container">
                            <a href="<?= URL_BASE ?>articulo.php?id=<?= $articulo['id'] ?>"
                                class="product-title"><?= htmlspecialchars($articulo['titulo']) ?></a>
                            <div class="price-container">
                                <span class="current-price">$ <?= number_format($articulo['precio'], 2, ',', '.') ?></span>
                            </div>
                            <?php if ($articulo['envio_gratis']): ?>
                                <span class="shipping">Envío gratis</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Otro contenido abajo -->
<div class="main-content">
    <div class="panel">
        <div class="panels-container">
            <h2 class="text-2xl font-bold mb-4 text-center">Recientes</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <?php foreach (array_slice($articulos, 0, 4) as $articulo): ?>
                    <div class="product-card-container">
                        <div class="image-placeholder-replica">
                            <a href="<?= URL_BASE ?>articulo.php?id=<?= $articulo['id'] ?>">
                                <?php
                                $imagenes = $articuloModel->getImagenes($articulo['id']);
                                if (!empty($imagenes)): ?>
                                    <img src="<?= URL_BASE ?>uploads/articulos/<?= $imagenes[0]['ruta_imagen'] ?>"
                                        alt="<?= htmlspecialchars($articulo['titulo']) ?>"
                                        style="width:100%; height:100%; object-fit:cover;">
                                <?php else: ?>
                                    <span>Imagen</span>
                                <?php endif; ?>
                            </a>
                        </div>
                        <div class="content-container">
                            <a href="<?= URL_BASE ?>articulo.php?id=<?= $articulo['id'] ?>"
                                class="product-title"><?= htmlspecialchars($articulo['titulo']) ?></a>
                            <div class="price-container">
                                <span class="current-price">$ <?= number_format($articulo['precio'], 2, ',', '.') ?></span>
                            </div>
                            <?php if ($articulo['envio_gratis']): ?>
                                <span class="shipping">Envío gratis</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panels-container">
            <h2 class="text-2xl font-bold mb-4 text-center">Los mas vistos</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <?php foreach (array_slice($articulos, 0, 4) as $articulo): ?>
                    <div class="product-card-container">
                        <div class="image-placeholder-replica">
                            <a href="<?= URL_BASE ?>articulo.php?id=<?= $articulo['id'] ?>">
                                <?php
                                $imagenes = $articuloModel->getImagenes($articulo['id']);
                                if (!empty($imagenes)): ?>
                                    <img src="<?= URL_BASE ?>uploads/articulos/<?= $imagenes[0]['ruta_imagen'] ?>"
                                        alt="<?= htmlspecialchars($articulo['titulo']) ?>"
                                        style="width:100%; height:100%; object-fit:cover;">
                                <?php else: ?>
                                    <span>Imagen</span>
                                <?php endif; ?>
                            </a>
                        </div>
                        <div class="content-container">
                            <a href="<?= URL_BASE ?>articulo.php?id=<?= $articulo['id'] ?>"
                                class="product-title"><?= htmlspecialchars($articulo['titulo']) ?></a>
                            <div class="price-container">
                                <span class="current-price">$ <?= number_format($articulo['precio'], 2, ',', '.') ?></span>
                            </div>
                            <?php if ($articulo['envio_gratis']): ?>
                                <span class="shipping">Envío gratis</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panels-container">
            <h2 class="text-2xl font-bold mb-4 text-center">Te pueden interesar</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <?php foreach (array_slice($articulos, 0, 4) as $articulo): ?>
                    <div class="product-card-container">
                        <div class="image-placeholder-replica">
                            <a href="<?= URL_BASE ?>articulo.php?id=<?= $articulo['id'] ?>">
                                <?php
                                $imagenes = $articuloModel->getImagenes($articulo['id']);
                                if (!empty($imagenes)): ?>
                                    <img src="<?= URL_BASE ?>uploads/articulos/<?= $imagenes[0]['ruta_imagen'] ?>"
                                        alt="<?= htmlspecialchars($articulo['titulo']) ?>"
                                        style="width:100%; height:100%; object-fit:cover;">
                                <?php else: ?>
                                    <span>Imagen</span>
                                <?php endif; ?>
                            </a>
                        </div>
                        <div class="content-container">
                            <a href="<?= URL_BASE ?>articulo.php?id=<?= $articulo['id'] ?>"
                                class="product-title"><?= htmlspecialchars($articulo['titulo']) ?></a>
                            <div class="price-container">
                                <span class="current-price">$ <?= number_format($articulo['precio'], 2, ',', '.') ?></span>
                            </div>
                            <?php if ($articulo['envio_gratis']): ?>
                                <span class="shipping">Envío gratis</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Banner en la parte inferior -->
<div
    style="width: 1200px; height: 400px; background-color: #28a745; margin: 20px auto; display: flex; align-items: center; justify-content: center; color: white; font-size: 48px; font-weight: bold;">
    Imagen
</div>

<?php require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'footer.php'; ?>