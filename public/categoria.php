<?php
header('Content-Type: text/html; charset=utf-8');
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'db.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'auth.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Articulo.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Categoria.php';

$categoria_id = intval($_GET['id'] ?? 0);
if ($categoria_id <= 0) {
    header("Location: " . URL_BASE);
    exit;
}

$categoriaModel = new Categoria();
$categoria = $categoriaModel->getById($categoria_id);
if (!$categoria) {
    header("Location: " . URL_BASE);
    exit;
}

$articuloModel = new Articulo();
$articuloModel->query("SELECT a.*, c.nombre as categoria_nombre 
                      FROM articulos a 
                      LEFT JOIN categorias c ON a.categoria_id = c.id 
                      WHERE a.categoria_id = :categoria_id AND a.estado = 'activo'");
$articuloModel->bind(':categoria_id', $categoria_id);
$articulos = $articuloModel->resultSet();

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'header.php';
?>

<h2>Categoría: <?= htmlspecialchars($categoria['nombre']) ?></h2>
<?php if (empty($articulos)): ?>
    <div style="background:white; padding:2rem; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.1); max-width:800px; margin:2rem auto; text-align:center;">
        <p>📦 No hay artículos en esta categoría aún.</p>
        <a href="<?= URL_BASE ?>" style="display:inline-block; background:#4DB6AC; color:white; padding:0.5rem 1rem; border-radius:4px; text-decoration:none; margin-top:1rem;">
            Explorar otras categorías
        </a>
    </div>
<?php else: ?>
    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap:1rem; margin:2rem 0;">
        <?php foreach ($articulos as $articulo): ?>
            <div style="background:white; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.1); padding:1rem;">
                <?php if (!empty($articulo['ml_item_id'])): ?>
                    <div style="background:#FFD700; padding:0.3rem 0.5rem; border-radius:4px; font-weight:bold; margin-bottom:0.5rem;">📦 Mercado Libre</div>
                <?php endif; ?>
                
                <!-- Imagen cuadrada -->
                <div style="width:100%; height:200px; background:#f0f0f0; display:flex; align-items:center; justify-content:center; margin-bottom:1rem; cursor:pointer;" onclick="location.href='<?= URL_BASE ?>articulo.php?id=<?= $articulo['id'] ?>'">
                    <?php
                    $imagenes = $articuloModel->getImagenes($articulo['id']);
                    if (!empty($imagenes)): ?>
                        <img src="<?= URL_BASE ?>uploads/articulos/<?= $imagenes[0]['ruta_imagen'] ?>" alt="<?= htmlspecialchars($articulo['titulo']) ?>" style="width:100%; height:100%; object-fit:cover;">
                    <?php else: ?>
                        <span style="color:#999;">Imagen del artículo</span>
                    <?php endif; ?>
                </div>
                
                <h3><?= htmlspecialchars($articulo['titulo']) ?></h3>
                <p style="font-size:1.2rem; font-weight:bold; color:#4DB6AC;">
                    $<?= number_format($articulo['precio'], 2, ',', '.') ?>
                </p>
                <p>Stock: <?= $articulo['stock'] ?></p>
                
                <div style="display:flex; gap:0.5rem; margin-top:1rem; align-items:center;">
                    <?php if (isLoggedIn() && $_SESSION['user_rol'] === 'usuario'): ?>
                        <button class="btn-toggle-favorito" data-id="<?= $articulo['id'] ?>" 
                                style="background:none; border:none; font-size:1.5rem; cursor:pointer; padding:0; margin:0;">
                            <span class="icon-favorito" data-id="<?= $articulo['id'] ?>">🤍</span>
                        </button>
                    <?php endif; ?>
                    <button class="btn-agregar-carrito" data-id="<?= $articulo['id'] ?>" 
                            style="flex:1; background:#64B5F6; color:white; padding:0.5rem; border:none; border-radius:4px; cursor:pointer;">
                        🛒 Carrito
                    </button>
                    <div class="dropdown" style="position:relative;">
                        <button style="background:#FFB74D; color:white; padding:0.5rem; border:none; border-radius:4px; cursor:pointer;">
                            📤 Compartir
                        </button>
                        <div class="dropdown-content" style="display:none; position:absolute; bottom:100%; left:0; background:white; box-shadow:0 2px 10px rgba(0,0,0,0.1); border-radius:4px; padding:0.5rem; min-width:150px; z-index:1000;">
                            <a href="https://api.whatsapp.com/send?text=<?= urlencode('Mira este artículo en MegaTec: ' . $articulo['titulo'] . ' - ' . URL_BASE . 'articulo.php?id=' . $articulo['id']) ?>" 
                               target="_blank" style="display:block; padding:0.5rem; color:#25D366; text-decoration:none; border-bottom:1px solid #eee;">
                                📱 WhatsApp
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(URL_BASE . 'articulo.php?id=' . $articulo['id']) ?>" 
                               target="_blank" style="display:block; padding:0.5rem; color:#1877F2; text-decoration:none;">
                                👍 Facebook
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

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