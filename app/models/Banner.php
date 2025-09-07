<?php
header('Content-Type: text/html; charset=utf-8');
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'db.php';

class Banner extends Database {
    public function getAll($activo = true) {
        $this->query("SELECT * FROM banners WHERE activo = :activo ORDER BY orden ASC");
        $this->bind(':activo', $activo);
        return $this->resultSet();
    }

    public function getByUbicacion($ubicacion) {
        $this->query("SELECT * FROM banners WHERE ubicacion = :ubicacion AND activo = 1 ORDER BY orden ASC");
        $this->bind(':ubicacion', $ubicacion);
        return $this->resultSet();
    }

    public function getById($id) {
        $this->query("SELECT * FROM banners WHERE id = :id");
        $this->bind(':id', $id);
        return $this->single();
    }

    public function create($data) {
        $this->query("INSERT INTO banners (
            titulo, imagen_url, link_url, tipo, ubicacion, activo, orden
        ) VALUES (
            :titulo, :imagen_url, :link_url, :tipo, :ubicacion, :activo, :orden
        )");

        foreach ($data as $key => $value) {
            $this->bind(":$key", $value);
        }

        return $this->execute();
    }

    public function update($id, $data) {
        $set = "";
        foreach (array_keys($data) as $key) {
            $set .= "$key = :$key, ";
        }
        $set = rtrim($set, ', ');

        $this->query("UPDATE banners SET $set WHERE id = :id");
        $this->bind(':id', $id);

        foreach ($data as $key => $value) {
            $this->bind(":$key", $value);
        }

        return $this->execute();
    }

    public function delete($id) {
        // Eliminar archivo del sistema
        $banner = $this->getById($id);
        if ($banner && file_exists(RUTA_UPLOADS . 'banners/' . $banner['imagen_url'])) {
            unlink(RUTA_UPLOADS . 'banners/' . $banner['imagen_url']);
        }

        $this->query("DELETE FROM banners WHERE id = :id");
        $this->bind(':id', $id);
        return $this->execute();
    }
}
?>