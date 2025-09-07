<?php
require_once dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'auth.php';
require_once dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Categoria.php';

$contador_carrito = 0;
$contador_mensajes = 0;
$categoriaModel = new Categoria();
$categorias = $categoriaModel->getAll();

if (isLoggedIn()) {
    require_once dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Carrito.php';
    $carritoModel = new Carrito();
    $items = $carritoModel->getByUsuario($_SESSION['user_id']);
    $contador_carrito = count($items);

    require_once dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Chat.php';
    $chatModel = new Chat();
    $contador_mensajes = $chatModel->getUnreadCount($_SESSION['user_id']);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MegaTec</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?= URL_BASE ?>assets/css/new-style.css">
</head>

<body style="margin: 0; padding: 0;">

    <header class="header">
        <div class="header-content">
            <div class="logo-container">
                <a href="<?= URL_BASE ?>" class="text-black no-underline flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 497 570">
                        <path id="logoDot" fill="#333"
                            d="M443.5,459.7l-14.4-21.4,43-25V156l-128.8-75.1,12.3-21.4,141.4,82.1v286l-53.7,32ZM25.5,311.2H.5v-167.9L254,.5l57,32-12.7,23.4-44.7-26.7L25.5,157.7v153.5ZM248.7,570.5L.5,429.3v-69.7h25v55.4l223.1,126.8,144.8-82.1,12.7,21.4-157.4,89.4ZM345.4,295.2v-64.1l-55.4-32v-19.7l71.4-41.1,73.4,41.1v84.1l-73.4,41-16-9.3ZM345.4,424v-64.4l-18-10.7,18-9v-19.7l16,10.7,55.4-32,18,9v84.4l-73.4,41.1-16-9ZM177.3,327.5v-84.1l73.4-41.1,71.4,41.1v84.1l-73.4,41.1-71.4-41.1ZM62.9,391.6l1.7-84.1,16-9,55.4,32,18-10.7v19.7l18,10.7-18,9v64.4l-18,9-73-41.1ZM62.9,263.1l1.7-84.1,73.4-41,71.4,41v19.7l-55.4,32v62.4l-18,10.7-73-40.7Z" />
                    </svg>
                    <span class="logo-text">MegaTec</span>
                </a>
            </div>
            <div class="search-bar">
                <button id="categoryDropdownBtn" class="category-dropdown-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-menu">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
                <input type="text" placeholder="Buscar productos, marcas y más...">
                <div id="categoryDropdownMenu" class="category-dropdown-menu">
                    <?php foreach ($categorias as $categoria): ?>
                        <a
                            href="<?= URL_BASE ?>categoria.php?id=<?= $categoria['id'] ?>"><?= htmlspecialchars($categoria['nombre']) ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="nav-links">
                <?php if (isLoggedIn()): ?>
                    <span>Hola, <?= htmlspecialchars($_SESSION['user_nombre']) ?></span>

                    <?php if ($_SESSION['user_rol'] === 'usuario'): ?>
                        <a href="<?= URL_BASE ?>dashboard/usuario/carrito.php">
                            🛒 Carrito
                            <?php if ($contador_carrito > 0): ?>
                                <span>(<?= $contador_carrito ?>)</span>
                            <?php endif; ?>
                        </a>
                        <a href="<?= URL_BASE ?>dashboard/usuario/favoritos.php">❤️ Favoritos</a>
                    <?php endif; ?>

                    <?php if ($_SESSION['user_rol'] === 'operario' || $_SESSION['user_rol'] === 'administrador'): ?>
                        <a href="<?= URL_BASE ?>admin_panel.php">
                            📋 Panel
                        </a>
                        <a href="<?= URL_BASE ?>crear_articulo.php">➕ Crear Artículo</a>
                    <?php endif; ?>

                    <a href="<?= URL_BASE ?>dashboard/<?= $_SESSION['user_rol'] ?>/perfil.php">👤 Perfil</a>
                    <a href="<?= URL_BASE ?>chat.php">
                        💬 Chat
                        <?php if ($contador_mensajes > 0): ?>
                            <span>(<?= $contador_mensajes ?>)</span>
                        <?php endif; ?>
                    </a>

                    <a href="<?= URL_BASE ?>logout.php">Salir</a>
                <?php else: ?>
                    <a href="<?= URL_BASE ?>register.php">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-user-plus">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="8.5" cy="7" r="4"></circle>
                            <line x1="20" y1="8" x2="20" y2="14"></line>
                            <line x1="23" y1="11" x2="17" y2="11"></line>
                        </svg>
                        Registrarse
                    </a>
                    <a href="<?= URL_BASE ?>login.php">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-log-in">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                            <polyline points="10 17 15 12 10 7"></polyline>
                            <line x1="15" y1="12" x2="3" y2="12"></line>
                        </svg>
                        Iniciar sesion
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Banner below header -->
    <div
        style="width: 1200px; height: 400px; background-color: #007bff; margin: 0 auto; display: flex; align-items: center; justify-content: center; color: white; font-size: 48px; font-weight: bold;">
        Imagen
    </div>

    <div id="pageContainer" class="page-container" style="max-width: 1200px; margin: 0 auto;">