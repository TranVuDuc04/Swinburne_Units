<?php
class HitCounter {
    private $conn;
    private $tableName;

    public function __construct($host, $username, $password, $dbname, $tableName) {
        $this->tableName = $tableName;
        $this->conn = new mysqli($host, $username, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getHits() {
        $query = "SELECT hits FROM $this->tableName WHERE id=1";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row['hits'];
    }

    public function setHits() {
        $query = "UPDATE $this->tableName SET hits = hits + 1 WHERE id=1";
        $this->conn->query($query);
    }

    public function closeConnection() {
        $this->conn->close();
    }

    public function startOver() {
        $query = "UPDATE $this->tableName SET hits = 0 WHERE id=1";
        $this->conn->query($query);
    }
}
?>
