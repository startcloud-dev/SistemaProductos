<?php
class Conexion {

private $host = "localhost";
private $port = 5432;
private $dbname = "SistemaProductos";
private $user = "postgres";
private $password = "admin";
public $conn;

    public function getConexion() {
        try {
            $dsn = "pgsql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->dbname;
            $this->conn = new PDO($dsn, $this->user, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }
        return $this->conn;
    }
}




