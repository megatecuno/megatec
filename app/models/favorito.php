<?php
header('Content-Type: text/html; charset=utf-8');
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'db.php';

class Favorito extends Database {
    public function agregar($usuario_id, $articulo_id) {
        $this->query("INSERT IGNORE INTO favoritos (usuario_id, articulo_id) VALUES (:usuario_id, :articulo_id)");
        $this->bind(':usuario_id', $usuario_id);
        $this->bind(':articulo_id', $articulo_id);
        return $this->execute();
    }

    public function eliminar($usuario_id, $articulo_id) {
        $this->query("DELETE FROM favoritos WHERE usuario_id = :usuario_id AND articulo_id = :articulo_id");
        $this->bind(':usuario_id', $usuario_id);
        $this->bind(':articulo_id', $articulo_id);
        return $this->execute();
    }

    public function existe($usuario_id, $articulo_id) {
        $this->query("SELECT id FROM favoritos WHERE usuario_id = :usuario_id AND articulo_id = :articulo_id");
        $this->bind(':usuario_id', $usuario_id);
        $this->bind(':articulo_id', $articulo_id);
        return $this->single() !== false;
    }

    public function getByUsuario($usuario_id) {
        $this->query("SELECT f.*, a.titulo, a.precio, a.ml_item_id, a.stock, a.categoria_id, c.nombre as categoria_nombre
                      FROM favoritos f
                      JOIN articulos a ON f.articulo_id = a.id
                      LEFT JOIN categorias c ON a.categoria_id = c.id
                      WHERE f.usuario_id = :usuario_id");
        $this->bind(':usuario_id', $usuario_id);
        return $this->resultSet();
    }
}
?>