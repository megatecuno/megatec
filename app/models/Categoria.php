<?php
header('Content-Type: text/html; charset=utf-8');
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'db.php';

class Categoria extends Database {
    public function getAll() {
        $this->query("SELECT * FROM categorias ORDER BY nombre ASC");
        return $this->resultSet();
    }

    public function getById($id) {
        $this->query("SELECT * FROM categorias WHERE id = :id");
        $this->bind(':id', $id);
        return $this->single();
    }

    public function create($nombre, $descripcion = '') {
        $this->query("INSERT INTO categorias (nombre, descripcion) VALUES (:nombre, :descripcion)");
        $this->bind(':nombre', $nombre);
        $this->bind(':descripcion', $descripcion);
        return $this->execute();
    }
}
?>