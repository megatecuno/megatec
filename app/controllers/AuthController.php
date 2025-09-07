<?php
header('Content-Type: text/html; charset=utf-8');
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Usuario.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'auth.php';

class AuthController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = $this->usuarioModel->login($email, $password);
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_nombre'] = $user['nombre'];
                $_SESSION['user_rol'] = $user['rol'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_avatar'] = $user['avatar'];
                redirectByRole();
            } else {
                $error = "Credenciales incorrectas.";
                require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'auth' . DIRECTORY_SEPARATOR . 'login.php';
            }
        } else {
            require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'auth' . DIRECTORY_SEPARATOR . 'login.php';
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            if ($password !== $confirm_password) {
                $error = "Las contraseñas no coinciden.";
                require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'auth' . DIRECTORY_SEPARATOR . 'register.php';
                return;
            }

            if ($this->usuarioModel->create($nombre, $email, $password)) {
                $_SESSION['mensaje'] = "Registro exitoso. Por favor inicia sesión.";
                header("Location: " . URL_BASE . "login.php");
                exit;
            } else {
                $error = "Error al registrar. El email ya existe.";
                require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'auth' . DIRECTORY_SEPARATOR . 'register.php';
            }
        } else {
            require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'auth' . DIRECTORY_SEPARATOR . 'register.php';
        }
    }

    public function logout() {
        session_destroy();
        header("Location: " . URL_BASE);
        exit;
    }

    public function perfil() {
        requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar'])) {
            $avatar = $_FILES['avatar'];
            if ($avatar['error'] === UPLOAD_ERR_OK) {
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
                $fileExt = strtolower(pathinfo($avatar['name'], PATHINFO_EXTENSION));
                
                if (in_array($fileExt, $allowedTypes)) {
                    $newFileName = 'avatar_' . $_SESSION['user_id'] . '.' . $fileExt;
                    $uploadPath = RUTA_UPLOADS . 'avatares/' . $newFileName;
                    
                    // Eliminar avatar anterior si existe
                    $avatarDir = RUTA_UPLOADS . 'avatares/';
                    $files = glob($avatarDir . 'avatar_' . $_SESSION['user_id'] . '.*');
                    foreach ($files as $file) {
                        if (file_exists($file)) {
                            unlink($file);
                        }
                    }
                    
                    if (move_uploaded_file($avatar['tmp_name'], $uploadPath)) {
                        $this->usuarioModel->updateAvatar($_SESSION['user_id'], $newFileName);
                        $_SESSION['user_avatar'] = $newFileName;
                        $_SESSION['mensaje'] = "Avatar actualizado correctamente.";
                    } else {
                        $_SESSION['error'] = "Error al subir el avatar.";
                    }
                } else {
                    $_SESSION['error'] = "Tipo de archivo no permitido. Solo JPG, JPEG, PNG, GIF.";
                }
            } else {
                $_SESSION['error'] = "Error al subir el archivo.";
            }
        }

        $usuario = $this->usuarioModel->getById($_SESSION['user_id']);
        require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'header.php';
        ?>

        <h2>Mi Perfil</h2>
        <div style="background:white; padding:2rem; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.1); max-width:600px; margin:2rem auto;">
            <div style="display:flex; align-items:center; gap:2rem; margin-bottom:2rem;">
                <div style="width:100px; height:100px; background:#ddd; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                    <?php if (!empty($_SESSION['user_avatar']) && file_exists(RUTA_UPLOADS . 'avatares/' . $_SESSION['user_avatar'])): ?>
                        <img src="<?= URL_BASE ?>uploads/avatares/<?= $_SESSION['user_avatar'] ?>" alt="Avatar" style="width:100%; height:100%; border-radius:50%; object-fit:cover;">
                    <?php else: ?>
                        <span>👤</span>
                    <?php endif; ?>
                </div>
                <div>
                    <h3><?= htmlspecialchars($usuario['nombre']) ?></h3>
                    <p><strong>Email:</strong> <?= htmlspecialchars($usuario['email']) ?></p>
                    <p><strong>Rol:</strong> <?= ucfirst($usuario['rol']) ?></p>
                </div>
            </div>
            
            <form method="POST" enctype="multipart/form-data" style="margin-bottom:2rem;">
                <div style="margin-bottom:1rem;">
                    <label>Actualizar Avatar:</label><br>
                    <input type="file" name="avatar" accept="image/*" style="padding:0.5rem; width:100%;">
                </div>
                <button type="submit" style="background:#4DB6AC; color:white; padding:0.5rem 1rem; border:none; border-radius:4px; cursor:pointer;">
                    Actualizar Avatar
                </button>
            </form>
            
            <a href="<?= URL_BASE ?>logout.php" style="display:inline-block; background:#E57373; color:white; padding:0.5rem 1rem; border-radius:4px; text-decoration:none; margin-top:1rem;">
                Cerrar Sesión
            </a>
        </div>

        <?php require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'footer.php'; ?>
        <?php
    }
}
?>