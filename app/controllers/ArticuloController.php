<?php
header('Content-Type: text/html; charset=utf-8');
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Articulo.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Categoria.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'auth.php';

class ArticuloController {
    private $articuloModel;
    private $categoriaModel;

    public function __construct() {
        $this->articuloModel = new Articulo();
        $this->categoriaModel = new Categoria();
    }

    public function crear() {
        requireLogin();
        if ($_SESSION['user_rol'] !== 'operario' && $_SESSION['user_rol'] !== 'administrador') {
            header("Location: " . URL_BASE);
            exit;
        }

        $categorias = $this->categoriaModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'titulo' => $_POST['titulo'],
                'descripcion' => $_POST['descripcion'],
                'precio' => floatval($_POST['precio']),
                'stock' => intval($_POST['stock']),
                'condicion' => $_POST['condicion'],
                'envio_gratis' => isset($_POST['envio_gratis']) ? 1 : 0,
                'creador_id' => $_SESSION['user_id'],
                'categoria_id' => intval($_POST['categoria_id']),
                'estado' => 'activo',
                'ml_item_id' => null
            ];

            $articulo_id = $this->articuloModel->create($data);
            if ($articulo_id && isset($_FILES['imagenes'])) {
                $orden = 1;
                foreach ($_FILES['imagenes']['name'] as $key => $name) {
                    if ($_FILES['imagenes']['error'][$key] === UPLOAD_ERR_OK) {
                        $file = [
                            'name' => $_FILES['imagenes']['name'][$key],
                            'type' => $_FILES['imagenes']['type'][$key],
                            'tmp_name' => $_FILES['imagenes']['tmp_name'][$key],
                            'error' => $_FILES['imagenes']['error'][$key],
                            'size' => $_FILES['imagenes']['size'][$key]
                        ];
                        
                        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
                        $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                        
                        if (in_array($fileExt, $allowedTypes)) {
                            $newFileName = 'articulo_' . $articulo_id . '_' . $orden . '.' . $fileExt;
                            $uploadPath = RUTA_UPLOADS . 'articulos/' . $newFileName;
                            
                            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                                $this->articuloModel->addImagen($articulo_id, $newFileName, $orden);
                                $orden++;
                            }
                        }
                    }
                }
            }

            if ($articulo_id) {
                $_SESSION['mensaje'] = "Artículo creado correctamente.";
            } else {
                $_SESSION['error'] = "Error al crear el artículo.";
            }
            header("Location: " . URL_BASE . "dashboard/" . $_SESSION['user_rol'] . "/index.php");
            exit;
        }

        require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'header.php';
        ?>

        <h2>Crear Nuevo Artículo</h2>
        <div style="background:white; padding:2rem; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.1); max-width:800px; margin:2rem auto;">
            <form method="POST" enctype="multipart/form-data">
                <div style="margin-bottom:1rem;">
                    <label>Título:</label><br>
                    <input type="text" name="titulo" required style="padding:0.5rem; width:100%;">
                </div>
                <div style="margin-bottom:1rem;">
                    <label>Categoría:</label><br>
                    <select name="categoria_id" required style="padding:0.5rem; width:100%;">
                        <option value="">Seleccionar categoría</option>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?= $categoria['id'] ?>"><?= htmlspecialchars($categoria['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="margin-bottom:1rem;">
                    <label>Descripción:</label><br>
                    <textarea name="descripcion" required style="padding:0.5rem; width:100%; height:100px;"></textarea>
                </div>
                <div style="margin-bottom:1rem;">
                    <label>Precio:</label><br>
                    <input type="number" name="precio" step="0.01" required style="padding:0.5rem; width:100%;">
                </div>
                <div style="margin-bottom:1rem;">
                    <label>Stock:</label><br>
                    <input type="number" name="stock" required style="padding:0.5rem; width:100%;">
                </div>
                <div style="margin-bottom:1rem;">
                    <label>Condición:</label><br>
                    <select name="condicion" required style="padding:0.5rem; width:100%;">
                        <option value="nuevo">Nuevo</option>
                        <option value="usado">Usado</option>
                        <option value="reacondicionado">Reacondicionado</option>
                    </select>
                </div>
                <div style="margin-bottom:1rem;">
                    <label><input type="checkbox" name="envio_gratis"> Envío gratis</label>
                </div>
                <div style="margin-bottom:1rem;">
                    <label>Imágenes (máximo 4):</label><br>
                    <input type="file" name="imagenes[]" accept="image/*" multiple style="padding:0.5rem; width:100%;">
                </div>
                <button type="submit" style="background:#4DB6AC; color:white; padding:0.5rem 1rem; border:none; border-radius:4px; cursor:pointer;">
                    Crear Artículo
                </button>
            </form>
        </div>

        <?php require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'footer.php'; ?>
        <?php
    }

    public function ver($id) {
        $articulo = $this->articuloModel->getById($id);
        $imagenes = $this->articuloModel->getImagenes($id);
        require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'articulo' . DIRECTORY_SEPARATOR . 'ver.php';
    }
}
?>