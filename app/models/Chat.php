<?php
header('Content-Type: text/html; charset=utf-8');
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'db.php';

class Chat extends Database {
    public function sendMessage($remitente_id, $destinatario_id, $articulo_id, $mensaje) {
        $this->query("INSERT INTO chat (remitente_id, destinatario_id, articulo_id, mensaje, fecha_envio) 
                      VALUES (:remitente_id, :destinatario_id, :articulo_id, :mensaje, NOW())");
        $this->bind(':remitente_id', $remitente_id);
        $this->bind(':destinatario_id', $destinatario_id);
        $this->bind(':articulo_id', $articulo_id);
        $this->bind(':mensaje', $mensaje);
        return $this->execute();
    }

    public function getMessages($articulo_id, $usuario_id = null) {
        $sql = "SELECT c.*, u.nombre as remitente_nombre, u.rol as remitente_rol
                FROM chat c
                JOIN usuarios u ON c.remitente_id = u.id
                WHERE c.articulo_id = :articulo_id";

        if ($usuario_id) {
            $sql .= " AND (c.remitente_id = :usuario_id OR c.destinatario_id = :usuario_id)";
        }

        $sql .= " ORDER BY c.fecha_envio ASC";

        $this->query($sql);
        $this->bind(':articulo_id', $articulo_id);
        if ($usuario_id) {
            $this->bind(':usuario_id', $usuario_id);
        }
        return $this->resultSet();
    }

    public function getUnreadCount($usuario_id) {
        $this->query("SELECT COUNT(*) as total 
                      FROM chat 
                      WHERE destinatario_id = :usuario_id AND leido = 0");
        $this->bind(':usuario_id', $usuario_id);
        $result = $this->single();
        return $result['total'];
    }
}
?>