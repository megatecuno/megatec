<?php require_once dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'header.php'; ?>

<div class="main-content">
    <div class="main-container">
        <!-- Contenido principal (izquierda) -->
        <div>
            <!-- Sección de la tarjeta del producto -->
            <div class="card">
                <div class="product-info">
                    <!-- Galería de imágenes -->
                    <div class="image-gallery">
                        <div id="mainImage" class="main-image" onclick="openGallery(0)">
                            <?php if (!empty($imagenes)): ?>
                                <img src="<?= URL_BASE ?>uploads/articulos/<?= $imagenes[0]['ruta_imagen'] ?>"
                                    alt="<?= htmlspecialchars($articulo['titulo']) ?>">
                            <?php else: ?>
                                <img src="https://placehold.co/600x400/87CEEB/fff?text=Imagen+no+disponible"
                                    alt="Imagen no disponible">
                            <?php endif; ?>
                        </div>
                        <div class="thumbnail-list">
                            <?php foreach ($imagenes as $index => $imagen): ?>
                                <div class="thumbnail <?= $index === 0 ? 'active' : '' ?>"
                                    onclick="selectImage(this, <?= $index ?>)">
                                    <img src="<?= URL_BASE ?>uploads/articulos/<?= $imagen['ruta_imagen'] ?>"
                                        alt="Thumbnail <?= $index + 1 ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Detalles del producto -->
                    <div class="product-details">
                        <span class="text-sm text-gray-500"><?= ucfirst($articulo['condicion']) ?> | +100
                            vendidos</span>
                        <div class="thin-separator"></div>
                        <h1 id="productTitle" class="text-xl font-semibold -mt-2">
                            <?= htmlspecialchars($articulo['titulo']) ?></h1>
                        <div class="thin-separator"></div>
                        <div class="flex flex-col gap-1">
                            <span class="price">$ <?= number_format($articulo['precio'], 2, ',', '.') ?></span>
                        </div>
                        <div class="thin-separator"></div>
                        <?php if ($articulo['envio_gratis']): ?>
                            <span class="text-sm mt-1 text-gray-600">Envío gratis</span>
                        <?php endif; ?>
                        <span class="payment-options text-sm">Ver los medios de pago</span>
                        <div class="thin-separator"></div>
                        <div class="mt-4 flex flex-col gap-2">
                            <button class="buy-button">Comprar ahora</button>
                            <button class="add-to-cart">Agregar al carrito</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sección de preguntas y respuestas -->
            <div class="qa-section">
                <h2 class="text-xl font-bold mb-4">Sacate tus dudas...</h2>
                <div class="flex flex-col gap-4">
                    <p class="text-gray-700">Si tienes alguna duda sobre el producto o necesitas concretar tu pedido, no
                        dudes en comunicarte con nuestros operadores. ¡Estamos aquí para ayudarte!</p>
                    <div class="flex gap-2 flex-wrap">
                        <span
                            class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm cursor-pointer hover:bg-gray-300 transition-colors">Costo
                            y tiempo de envío</span>
                        <span
                            class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm cursor-pointer hover:bg-gray-300 transition-colors">Devoluciones
                            gratis</span>
                        <span
                            class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm cursor-pointer hover:bg-gray-300 transition-colors">Medios
                            de pago</span>
                        <span
                            class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm cursor-pointer hover:bg-gray-300 transition-colors">Garantía</span>
                    </div>
                    <div class="flex items-center gap-2 mt-4">
                        <input id="questionInput" type="text" placeholder="Escribe tu pregunta"
                            class="question-input flex-1">
                        <button onclick="askGemini()" class="ask-button">Preguntar ✨</button>
                    </div>
                    <div id="answerArea" class="mt-4 p-4 rounded-md bg-gray-100 text-gray-800 hidden">
                        <div class="font-semibold text-sm mb-2">Respuesta del asistente:</div>
                        <div id="geminiAnswer"></div>
                    </div>
                </div>
            </div>

            <!-- Línea separadora -->
            <div class="separator"></div>

            <!-- Sección de productos del mismo vendedor -->
            <div class="related-products">
                <h2 class="text-xl font-bold mb-4">Publicaciones del vendedor</h2>
                <div class="product-grid">
                    <!-- Aquí se pueden cargar productos relacionados dinámicamente -->
                </div>
            </div>
        </div>

        <!-- Barra lateral (derecha) -->
        <div>
            <aside class="banner-panel">
                <h2 class="text-xl font-bold">Te puede interesar</h2>
                <div class="banner-placeholder">
                    <span>Banner publicitario (300x700)</span>
                </div>
            </aside>
            <aside class="card mt-5">
                <h2 class="text-xl font-bold">Publicaciones, artículos</h2>
                <div class="mt-4 text-gray-600 product-card-replica">
                    <!-- Aquí se puede cargar una réplica de tarjeta de producto -->
                </div>
            </aside>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'footer.php'; ?>