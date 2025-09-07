<?php
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Usuario.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'PermisoOperario.php';

class OperarioController {
    private $usuarioModel;
    private $permisoOperarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
        $this->permisoOperarioModel = new PermisoOperario();
    }

    public function gestionar() {
        $operarios = $this->usuarioModel->getByRole('operario');
        foreach ($operarios as &$operario) {
            $permisos = $this->permisoOperarioModel->getByOperarioId($operario['id']);
            if (!$permisos) {
                $this->permisoOperarioModel->create($operario['id']);
                $permisos = $this->permisoOperarioModel->getByOperarioId($operario['id']);
            }
            $operario['permisos'] = $permisos;
        }

        require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'dashboard' . DIRECTORY_SEPARATOR . 'administrador' . DIRECTORY_SEPARATOR . 'operarios.php';
    }

    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $operario_id = $_POST['operario_id'];
            $permisos = [
                'subir_banners' => isset($_POST['subir_banners']) ? 1 : 0,
                'modificar_perfiles_usuarios' => isset($_POST['modificar_perfiles_usuarios']) ? 1 : 0,
                'usar_chat' => isset($_POST['usar_chat']) ? 1 : 0,
                'publicar_contenido' => isset($_POST['publicar_contenido']) ? 1 : 0,
                'cambiar_perfil' => isset($_POST['cambiar_perfil']) ? 1 : 0,
            ];

            $this->permisoOperarioModel->update($operario_id, $permisos);
            header("Location: " . URL_BASE . "dashboard/administrador/operarios.php");
            exit;
        }
    }
}
?>