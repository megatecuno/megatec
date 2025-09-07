<?php
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Usuario.php';

class UsuarioController
{
    private $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new Usuario();
    }

    public function index()
    {
        $usuarios = $this->usuarioModel->getAll();
        require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'dashboard' . DIRECTORY_SEPARATOR . 'administrador' . DIRECTORY_SEPARATOR . 'usuarios.php';
    }

    public function edit($id)
    {
        $usuario = $this->usuarioModel->getById($id);
        require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'dashboard' . DIRECTORY_SEPARATOR . 'administrador' . DIRECTORY_SEPARATOR . 'edit_usuario.php';
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $password = $_POST['password'];

            if (!empty($password)) {
                $this->usuarioModel->updatePassword($id, $password);
            }

            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                // Handle avatar upload
                $avatar = $_FILES['avatar'];
                $newFileName = 'avatar_' . $id . '.webp';
                $uploadPath = RUTA_UPLOADS . 'avatares/' . $newFileName;

                $image = imagecreatefromstring(file_get_contents($avatar['tmp_name']));
                $resizedImage = imagescale($image, 50, 50);

                if (imagewebp($resizedImage, $uploadPath, 50)) {
                    $this->usuarioModel->updateAvatar($id, $newFileName);
                }

                imagedestroy($image);
                imagedestroy($resizedImage);
            }

            $this->usuarioModel->updateNombre($id, $nombre);

            header("Location: " . URL_BASE . "dashboard/administrador/usuarios.php");
            exit;
        }
    }
}
