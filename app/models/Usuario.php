<?php
header('Content-Type: text/html; charset=utf-8');
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'db.php';

class Usuario extends Database
{
    public function login($email, $password)
    {
        $this->query("SELECT * FROM usuarios WHERE email = :email AND activo = 1");
        $this->bind(':email', $email);
        $user = $this->single();

        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);
            return $user;
        }
        return false;
    }

    public function create($nombre, $email, $password, $rol = 'usuario')
    {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $this->query("INSERT INTO usuarios (nombre, email, password, rol) VALUES (:nombre, :email, :password, :rol)");
        $this->bind(':nombre', $nombre);
        $this->bind(':email', $email);
        $this->bind(':password', $hashed);
        $this->bind(':rol', $rol);
        return $this->execute();
    }

    public function updateAvatar($usuario_id, $avatar)
    {
        $this->query("UPDATE usuarios SET avatar = :avatar WHERE id = :usuario_id");
        $this->bind(':avatar', $avatar);
        $this->bind(':usuario_id', $usuario_id);
        return $this->execute();
    }

    public function getById($id)
    {
        $this->query("SELECT id, nombre, email, rol, avatar FROM usuarios WHERE id = :id");
        $this->bind(':id', $id);
        return $this->single();
    }

    public function getByRole($rol)
    {
        $this->query("SELECT id, nombre, email, rol, avatar FROM usuarios WHERE rol = :rol");
        $this->bind(':rol', $rol);
        return $this->resultSet();
    }

    public function getAll()
    {
        $this->query("SELECT id, nombre, email, rol, avatar FROM usuarios");
        return $this->resultSet();
    }

    public function updatePassword($id, $password)
    {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $this->query("UPDATE usuarios SET password = :password WHERE id = :id");
        $this->bind(':password', $hashed);
        $this->bind(':id', $id);
        return $this->execute();
    }

    public function updateNombre($id, $nombre)
    {
        $this->query("UPDATE usuarios SET nombre = :nombre WHERE id = :id");
        $this->bind(':nombre', $nombre);
        $this->bind(':id', $id);
        return $this->execute();
    }
}
