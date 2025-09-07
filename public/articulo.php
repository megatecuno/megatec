<?php
header('Content-Type: text/html; charset=utf-8');
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'db.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'auth.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Articulo.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Usuario.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Favorito.php';

$articulo_id = intval($_GET['id'] ?? 0);
if ($articulo_id <= 0) {
    header("Location: " . URL_BASE);
    exit;
}

$articuloModel = new Articulo();
$articulo = $articuloModel->getById($articulo_id);
if (!$articulo) {
    header("Location: " . URL_BASE);
    exit;
}

$imagenes = $articuloModel->getImagenes($articulo_id);
$favoritoModel = new Favorito();
$es_favorito = isLoggedIn() && $_SESSION['user_rol'] === 'usuario' && $favoritoModel->existe($_SESSION['user_id'], $articulo_id);

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'header.php';
?>

<div style="display:grid; grid-template-columns: 1fr 300px; gap:2rem; margin:2rem 0;">
    <!-- Imagen grande -->
    <div>
        <?php if (!empty($imagenes)): ?>
            <div style="width:100%; height:400px; background:white; border-radius:8px; overflow:hidden; margin-bottom:1rem;">
                <img src="<?= URL_BASE ?>uploads/articulos/<?= $imagenes[0]['ruta_imagen'] ?>" alt="<?= htmlspecialchars($articulo['titulo']) ?>" style="width:100%; height:100%; object-fit:cover;">
            </div>
        <?php else: ?>
            <div style="width:100%; height:400px; background:#f0f0f0; display:flex; align-items:center; justify-content:center; margin-bottom:1rem; border-radius:8px;">
                <span style="color:#999; font-size:2rem;">Imagen del artículo</span>
            </div>
        <?php endif; ?>
        
        <h1><?= htmlspecialchars($articulo['titulo']) ?></h1>
        <p style="font-size:1.5rem; font-weight:bold; color:#4DB6AC;">
            $<?= number_format($articulo['precio'], 2, ',', '.') ?>
        </p>
        <p><strong>Stock:</strong> <?= $articulo['stock'] ?></p>
        <p><strong>Condición:</strong> <?= ucfirst($articulo['condicion']) ?></p>
        <p><strong>Categoría:</strong> <?= htmlspecialchars($articulo['categoria_nombre']) ?></p>
        <p><strong>Descripción:</strong></p>
        <p><?= nl2br(htmlspecialchars($articulo['descripcion'])) ?></p>
        
        <div style="margin-top:2rem; padding:1rem; background:#f0f0f0; border-radius:8px;">
            <h3>Información del vendedor</h3>
            <div style="display:flex; align-items:center; gap:1rem; margin-bottom:1rem;">
                <div style="width:50px; height:50px; background:#ddd; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                    <?php if (!empty($articulo['creador_avatar']) && file_exists(RUTA_UPLOADS . 'avatares/' . $articulo['creador_avatar'])): ?>
                        <img src="<?= URL_BASE ?>uploads/avatares/<?= $articulo['creador_avatar'] ?>" alt="Avatar" style="width:100%; height:100%; border-radius:50%; object-fit:cover;">
                    <?php else: ?>
                        <span>👤</span>
                    <?php endif; ?>
                </div>
                <div>
                    <p style="margin:0; font-weight:bold;"><?= htmlspecialchars($articulo['creador_nombre']) ?></p>
                    <p style="margin:0; font-size:0.9rem;"><?= ucfirst($articulo['creador_id'] == 1 ? 'Administrador' : ($articulo['creador_id'] == 2 ? 'Operario' : 'Usuario')) ?></p>
                </div>
            </div>
            <a href="<?= URL_BASE ?>ver_chat.php?articulo_id=<?= $articulo['id'] ?>" 
               style="display:inline-block; background:#4DB6AC; color:white; padding:0.5rem 1rem; text-decoration:none; border-radius:4px;">
                💬 Iniciar Chat
            </a>
        </div>
    </div>
    
    <!-- Panel lateral -->
    <div style="background:white; padding:1rem; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.1);">
        <div style="display:flex; gap:0.5rem; margin-bottom:1rem; align-items:center;">
            <?php if (isLoggedIn() && $_SESSION['user_rol'] === 'usuario'): ?>
                <button class="btn-toggle-favorito" data-id="<?= $articulo['id'] ?>" 
                        style="background:none; border:none; font-size:1.5rem; cursor:pointer; padding:0; margin:0;">
                    <span class="icon-favorito" data-id="<?= $articulo['id'] ?>">
                        <?= $es_favorito ? '❤️' : '🤍' ?>
                    </span>
                </button>
            <?php endif; ?>
            <button class="btn-agregar-carrito" data-id="<?= $articulo['id'] ?>" 
                    style="flex:1; background:#64B5F6; color:white; padding:0.75rem; border:none; border-radius:4px; cursor:pointer;">
                🛒 Agregar al Carrito
            </button>
        </div>
        
        <div class="dropdown" style="margin-bottom:1rem;">
            <button style="width:100%; background:#FFB74D; color:white; padding:0.75rem; border:none; border-radius:4px; cursor:pointer;">
                📤 Compartir
            </button>
            <div class="dropdown-content" style="display:none; position:absolute; bottom:100%; left:0; right:0; background:white; box-shadow:0 2px 10px rgba(0,0,0,0.1); border-radius:4px; padding:0.5rem; z-index:1000;">
                <a href="https://api.whatsapp.com/send?text=<?= urlencode('Mira este artículo en MegaTec: ' . $articulo['titulo'] . ' - ' . URL_BASE . 'articulo.php?id=' . $articulo['id']) ?>" 
                   target="_blank" style="display:block; padding:0.75rem; color:#25D366; text-decoration:none; border-bottom:1px solid #eee; text-align:center;">
                    📱 WhatsApp
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(URL_BASE . 'articulo.php?id=' . $articulo['id']) ?>" 
                   target="_blank" style="display:block; padding:0.75rem; color:#1877F2; text-decoration:none; text-align:center;">
                    👍 Facebook
                </a>
            </div>
        </div>
        
        <button style="width:100%; background:#4DB6AC; color:white; padding:0.75rem; border:none; border-radius:4px; cursor:pointer;">
            💬 Preguntar al vendedor
        </button>
    </div>
</div>

<script>
// Toggle favoritos
document.querySelectorAll('.btn-toggle-favorito').forEach(button => {
    button.addEventListener('click', function() {
        const articuloId = this.dataset.id;
        const icon = this.querySelector('.icon-favorito');
        
        fetch('<?= URL_BASE ?>toggle_favorito.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'articulo_id=' + articuloId
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'added') {
                icon.textContent = '❤️';
                icon.style.color = '#E57373';
            } else {
                icon.textContent = '🤍';
                icon.style.color = '';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al actualizar favoritos');
        });
    });
});

// Agregar al carrito
document.querySelectorAll('.btn-agregar-carrito').forEach(button => {
    button.addEventListener('click', function() {
        const articuloId = this.dataset.id;
        
        fetch('<?= URL_BASE ?>agregar_al_carrito.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'articulo_id=' + articuloId + '&cantidad=1&redirect=0'
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al agregar al carrito');
        });
    });
});

// Dropdown compartir
document.querySelectorAll('.dropdown').forEach(dropdown => {
    const button = dropdown.querySelector('button');
    const content = dropdown.querySelector('.dropdown-content');
    
    button.addEventListener('click', function(e) {
        e.stopPropagation();
        document.querySelectorAll('.dropdown-content').forEach(dc => {
            if (dc !== content) dc.style.display = 'none';
        });
        content.style.display = content.style.display === 'block' ? 'none' : 'block';
    });
});

// Cerrar dropdowns al hacer clic fuera
document.addEventListener('click', function() {
    document.querySelectorAll('.dropdown-content').forEach(dc => {
        dc.style.display = 'none';
    });
});
</script>

<?php require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'footer.php'; ?>