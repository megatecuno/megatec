<?php
header('Content-Type: text/html; charset=utf-8');
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'db.php';

class Carrito extends Database {
    public function agregar($usuario_id, $articulo_id, $cantidad = 1) {
        $this->query("INSERT INTO carrito (usuario_id, articulo_id, cantidad) 
                      VALUES (:usuario_id, :articulo_id, :cantidad)
                      ON DUPLICATE KEY UPDATE cantidad = cantidad + :cantidad");
        $this->bind(':usuario_id', $usuario_id);
        $this->bind(':articulo_id', $articulo_id);
        $this->bind(':cantidad', $cantidad);
        return $this->execute();
    }

    public function getByUsuario($usuario_id) {
        $this->query("SELECT c.*, a.titulo, a.precio, a.stock, a.ml_item_id
                      FROM carrito c
                      JOIN articulos a ON c.articulo_id = a.id
                      WHERE c.usuario_id = :usuario_id");
        $this->bind(':usuario_id', $usuario_id);
        return $this->resultSet();
    }

    public function getTotal($usuario_id) {
        $this->query("SELECT SUM(c.cantidad * a.precio) as total 
                      FROM carrito c
                      JOIN articulos a ON c.articulo_id = a.id
                      WHERE c.usuario_id = :usuario_id");
        $this->bind(':usuario_id', $usuario_id);
        $result = $this->single();
        return $result['total'] ?? 0;
    }

    public function eliminar($usuario_id, $articulo_id) {
        $this->query("DELETE FROM carrito WHERE usuario_id = :usuario_id AND articulo_id = :articulo_id");
        $this->bind(':usuario_id', $usuario_id);
        $this->bind(':articulo_id', $articulo_id);
        return $this->execute();
    }
}
?>