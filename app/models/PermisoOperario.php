<?php
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'db.php';

class PermisoOperario extends Database
{
    public function getByOperarioId($operario_id)
    {
        $this->query("SELECT * FROM permisos_operario WHERE operario_id = :operario_id");
        $this->bind(':operario_id', $operario_id);
        return $this->single();
    }

    public function create($operario_id)
    {
        $this->query("INSERT INTO permisos_operario (operario_id) VALUES (:operario_id)");
        $this->bind(':operario_id', $operario_id);
        return $this->execute();
    }

    public function update($operario_id, $permisos)
    {
        $this->query("UPDATE permisos_operario SET subir_banners = :subir_banners, modificar_perfiles_usuarios = :modificar_perfiles_usuarios, usar_chat = :usar_chat, publicar_contenido = :publicar_contenido, cambiar_perfil = :cambiar_perfil WHERE operario_id = :operario_id");
        $this->bind(':operario_id', $operario_id);
        $this->bind(':subir_banners', $permisos['subir_banners']);
        $this->bind(':modificar_perfiles_usuarios', $permisos['modificar_perfiles_usuarios']);
        $this->bind(':usar_chat', $permisos['usar_chat']);
        $this->bind(':publicar_contenido', $permisos['publicar_contenido']);
        $this->bind(':cambiar_perfil', $permisos['cambiar_perfil']);
        return $this->execute();
    }
}
