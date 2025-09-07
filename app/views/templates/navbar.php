<?php
header('Content-Type: text/html; charset=utf-8');
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
<nav style="background:#4DB6AC; padding:1rem; color:white; display:flex; justify-content:space-between; align-items:center; position:fixed; top:0; left:0; right:0; z-index:1000; box-shadow:0 2px 5px rgba(0,0,0,0.1);">
    <div style="display:flex; align-items:center; gap:0.5rem;">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 497 570" style="height:40px; fill:#3a80bf;">
            <path d="M443.5,459.7l-14.4-21.4,43-25V156l-128.8-75.1,12.3-21.4,141.4,82.1v286l-53.7,32ZM25.5,311.2H.5v-167.9L254,.5l57,32-12.7,23.4-44.7-26.7L25.5,157.7v153.5ZM248.7,570.5L.5,429.3v-69.7h25v55.4l223.1,126.8,144.8-82.1,12.7,21.4-157.4,89.4ZM345.4,295.2v-64.1l-55.4-32v-19.7l71.4-41.1,73.4,41.1v84.1l-73.4,41-16-9.3ZM345.4,424v-64.4l-18-10.7,18-9v-19.7l16,10.7,55.4-32,18,9v84.4l-73.4,41.1-16-9ZM177.3,327.5v-84.1l73.4-41.1,71.4,41.1v84.1l-73.4,41.1-71.4-41.1ZM62.9,391.6l1.7-84.1,16-9,55.4,32,18-10.7v19.7l18,10.7-18,9v64.4l-18,9-73-41.1ZM62.9,263.1l1.7-84.1,73.4-41,71.4,41v19.7l-55.4,32v62.4l-18,10.7-73-40.7Z"/>
        </svg>
        <a href="<?= URL_BASE ?>" style="color:#275783; text-decoration:none; font-weight:bold; font-size:1.2rem;">MegaTec</a>
    </div>
    
    <!-- Barra de búsqueda con menú de categorías -->
    <div style="position:relative; flex:1; max-width:400px; margin:0 1rem;">
        <div style="position:relative;">
            <button id="btnCategorias" style="position:absolute; left:10px; top:50%; transform:translateY(-50%); background:none; border:none; color:white; font-size:1.5rem; cursor:pointer;">☰</button>
            <form method="GET" action="<?= URL_BASE ?>buscar.php" style="display:block;">
                <input type="text" name="q" placeholder="Buscar en toda la web..." style="width:100%; padding:0.5rem 0.5rem 0.5rem 40px; border-radius:20px; border:1px solid white; background:rgba(255,255,255,0.2); color:white;">
            </form>
        </div>
        
        <!-- Overlay oscuro -->
        <div id="overlayCategorias" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); z-index:999;"></div>
        
        <!-- Menú de categorías -->
        <div id="menuCategorias" style="display:none; position:absolute; top:100%; left:0; right:0; background:white; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.1); z-index:1000; padding:1rem; max-height:400px; overflow-y:auto;">
            <h3 style="margin:0 0 1rem 0; padding-bottom:0.5rem; border-bottom:1px solid #ddd;">Categorías</h3>
            <div style="display:grid; grid-template-columns: 1fr; gap:0.5rem;">
                <?php foreach ($categorias as $categoria): ?>
                    <a href="<?= URL_BASE ?>categoria.php?id=<?= $categoria['id'] ?>" style="padding:0.75rem; text-decoration:none; color:#333; border-radius:4px; background:#f8f9fa; transition:background 0.3s;"><?= htmlspecialchars($categoria['nombre']) ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <div style="display:flex; gap:1rem; align-items:center;">
        <?php if (isLoggedIn()): ?>
            <span>Hola, <?= htmlspecialchars($_SESSION['user_nombre']) ?></span>
            
            <?php if ($_SESSION['user_rol'] === 'usuario'): ?>
                <a href="<?= URL_BASE ?>dashboard/usuario/carrito.php" style="color:white; position:relative;">
                    🛒 Carrito
                    <?php if ($contador_carrito > 0): ?>
                        <span style="background:#E57373; color:white; border-radius:50%; padding:0.2rem 0.5rem; font-size:0.8rem; position:absolute; top:-5px; right:-5px;">
                            <?= $contador_carrito ?>
                        </span>
                    <?php endif; ?>
                </a>
                <a href="<?= URL_BASE ?>dashboard/usuario/favoritos.php" style="color:white;">❤️ Favoritos</a>
            <?php endif; ?>
            
            <?php if ($_SESSION['user_rol'] === 'operario' || $_SESSION['user_rol'] === 'administrador'): ?>
                <a href="<?= URL_BASE ?>dashboard/<?= $_SESSION['user_rol'] ?>/index.php" style="color:white; display:flex; align-items:center; gap:0.5rem;">
                    📋 Panel
                </a>
                <a href="<?= URL_BASE ?>crear_articulo.php" style="color:white;">➕ Crear Artículo</a>
            <?php endif; ?>
            
            <a href="<?= URL_BASE ?>dashboard/<?= $_SESSION['user_rol'] ?>/perfil.php" style="color:white;">👤 Perfil</a>
            <a href="<?= URL_BASE ?>chat.php" style="color:white; position:relative;">
                💬 Chat
                <?php if ($contador_mensajes > 0): ?>
                    <span style="background:#E57373; color:white; border-radius:50%; padding:0.2rem 0.5rem; font-size:0.8rem; position:absolute; top:-5px; right:-5px;">
                        <?= $contador_mensajes ?>
                    </span>
                <?php endif; ?>
            </a>
            
            <a href="<?= URL_BASE ?>logout.php" style="color:white; background:#00796B; padding:0.5rem 1rem; border-radius:4px; text-decoration:none;">Salir</a>
        <?php else: ?>
            <a href="<?= URL_BASE ?>login.php" style="color:white;">Iniciar Sesión</a>
            <a href="<?= URL_BASE ?>register.php" style="color:white; background:#00796B; padding:0.5rem 1rem; border-radius:4px; text-decoration:none;">Registrarse</a>
        <?php endif; ?>
    </div>
</nav>
<div style="height:80px;"></div> <!-- Espacio para el header fijo -->

<script>
document.getElementById('btnCategorias').addEventListener('click', function(e) {
    e.preventDefault();
    const menu = document.getElementById('menuCategorias');
    const overlay = document.getElementById('overlayCategorias');
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    overlay.style.display = overlay.style.display === 'block' ? 'none' : 'block';
});

// Cerrar menú al hacer clic fuera o en el overlay
document.getElementById('overlayCategorias').addEventListener('click', function() {
    document.getElementById('menuCategorias').style.display = 'none';
    this.style.display = 'none';
});

document.addEventListener('click', function(e) {
    const menu = document.getElementById('menuCategorias');
    const btn = document.getElementById('btnCategorias');
    const overlay = document.getElementById('overlayCategorias');
    if (!menu.contains(e.target) && e.target !== btn && menu.style.display === 'block') {
        menu.style.display = 'none';
        overlay.style.display = 'none';
    }
});
</script>