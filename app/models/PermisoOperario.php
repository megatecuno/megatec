<?php
header('Content-Type: text/html; charset=utf-8');
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'db.php';

class PermisoOperario extends Database {
    public function getPermisos() {
        $this->query("SELECT * FROM permisos_operarios ORDER BY id DESC LIMIT 1");
        return $this->single();
    }

    public function updatePermisos($data) {
        $this->query("UPDATE permisos_operarios SET 
            puede_crear_articulos = :puede_crear_articulos,
            puede_usar_chat = :puede_usar_chat,
            puede_editar_usuarios = :puede_editar_usuarios,
            puede_subir_banners = :puede_subir_banners,
            fecha_actualizacion = NOW()
            WHERE id = 1");

        foreach ($data as $key => $value) {
            $this->bind(":$key", $value);
        }

        return $this->execute();
    }
}
?>