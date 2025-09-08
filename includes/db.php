<?php
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';

class Database
{
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh;
    private $stmt;

    public function __construct()
    {
        $dsn = "mysql:host=$this->host;dbname=$this->dbname;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
            $this->dbh->exec("SET NAMES 'utf8mb4'");
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public function query($sql)
    {
        $this->stmt = $this->dbh->prepare($sql);
    }

    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute()
    {
        if (!$this->stmt) {
            throw new Exception("No query prepared");
        }
        return $this->stmt->execute();
    }

    public function resultSet()
    {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    public function single()
    {
        $this->execute();
        return $this->stmt->fetch();
    }
}
