<?php
class Database {
    private $host = "localhost";
    private $dbname = "gameloggd";
    private $user = "root";
    private $password = "";
    private $conn = null;


    public function connect() {
        try {
            if ($this->conn == null) {
                $this->conn = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->user, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            return $this->conn;
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            return null;
        }
    }
}


?>