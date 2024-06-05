<?php
class Database {
    private $host = "localhost";
    private $db_name = "espdb";
    private $username = "root";
    private $password = "";
    public $conn;


    public function getConnection() {
        $this->conn = null;

        try {
            // Attempt to connect to the database
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            // Handle connection errors
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
