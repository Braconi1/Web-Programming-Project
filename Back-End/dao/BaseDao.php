<?php
require_once 'config.php';

class BaseDao {
    protected $table;
    protected $conn;

    public function __construct($table) {
        $this->table = $table;
        $this->conn = Database::connect();
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id, $col = 'id') {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table . " WHERE $col = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($data) {
        $columns = implode(", ", array_keys($data));
        $values = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO " . $this->table . " ($columns) VALUES ($values)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        return $this->conn->lastInsertId();
    }

    public function update($id, $data, $col = 'id') {
        $set = "";
        foreach ($data as $key => $val) {
            $set .= "$key = :$key, ";
        }
        $set = rtrim($set, ", ");
        $sql = "UPDATE " . $this->table . " SET $set WHERE $col = :id";
        $stmt = $this->conn->prepare($sql);
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public function delete($id, $col = 'id') {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table . " WHERE $col = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function countRows() {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM " . $this->table);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['total'] : 0;
    }

    public function getVoteDetails() {
        $sql = "SELECT 
                    v.vote_id,
                    u.full_name AS user_name,
                    c.full_name AS candidate_name,
                    p.party_name
                FROM votes v
                JOIN users u ON v.user_id = u.user_id
                JOIN candidates c ON v.candidate_id = c.candidate_id
                JOIN parties p ON c.party_id = p.party_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
