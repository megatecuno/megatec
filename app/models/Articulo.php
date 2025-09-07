<?php
header('Content-Type: text/html; charset=utf-8');
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'db.php';

class Articulo extends Database {
    public function getAll($estado = 'activo') {
        $this->query("SELECT a.*, c.nombre as categoria_nombre 
                      FROM articulos a 
                      LEFT JOIN categorias c ON a.categoria_id = c.id 
                      WHERE a.estado = :estado");
        $this->bind(':estado', $estado);
        return $this->resultSet();
    }

    public function getById($id) {
        $this->query("SELECT a.*, c.nombre as categoria_nombre, u.nombre as creador_nombre, u.avatar as creador_avatar
                      FROM articulos a 
                      LEFT JOIN categorias c ON a.categoria_id = c.id
                      LEFT JOIN usuarios u ON a.creador_id = u.id
                      WHERE a.id = :id");
        $this->bind(':id', $id);
        return $this->single();
    }

    public function create($data) {
        $this->query("INSERT INTO articulos (
            titulo, descripcion, precio, stock, condicion, 
            envio_gratis, creador_id, categoria_id, estado, fecha_creacion, ml_item_id
        ) VALUES (
            :titulo, :descripcion, :precio, :stock, :condicion, 
            :envio_gratis, :creador_id, :categoria_id, :estado, NOW(), :ml_item_id
        )");

        foreach ($data as $key => $value) {
            $this->bind(":$key", $value);
        }

        if ($this->execute()) {
            return $this->dbh->lastInsertId();
        }
        return false;
    }

    public function delete($id) {
        // Primero obtener las imágenes del artículo
        $this->query("SELECT ruta_imagen FROM imagenes_articulo WHERE articulo_id = :articulo_id");
        $this->bind(':articulo_id', $id);
        $imagenes = $this->resultSet();

        // Eliminar imágenes del sistema de archivos
        foreach ($imagenes as $imagen) {
            $ruta = RUTA_UPLOADS . 'articulos/' . $imagen['ruta_imagen'];
            if (file_exists($ruta)) {
                unlink($ruta);
            }
        }

        // Eliminar imágenes de la base de datos
        $this->query("DELETE FROM imagenes_articulo WHERE articulo_id = :articulo_id");
        $this->bind(':articulo_id', $id);
        $this->execute();

        // Eliminar artículo
        $this->query("DELETE FROM articulos WHERE id = :id");
        $this->bind(':id', $id);
        return $this->execute();
    }

    public function getImagenes($articulo_id) {
        $this->query("SELECT * FROM imagenes_articulo WHERE articulo_id = :articulo_id ORDER BY orden ASC");
        $this->bind(':articulo_id', $articulo_id);
        return $this->resultSet();
    }

    public function addImagen($articulo_id, $ruta_imagen, $orden = 1) {
        $this->query("INSERT INTO imagenes_articulo (articulo_id, ruta_imagen, orden) VALUES (:articulo_id, :ruta_imagen, :orden)");
        $this->bind(':articulo_id', $articulo_id);
        $this->bind(':ruta_imagen', $ruta_imagen);
        $this->bind(':orden', $orden);
        return $this->execute();
    }
}
?>