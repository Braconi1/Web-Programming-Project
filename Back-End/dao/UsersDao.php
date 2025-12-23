<?php
require_once 'BaseDao.php';

class UsersDao extends BaseDao {

    public function __construct() {
        parent::__construct("users");
    }

    public function getUserById($id) {
        return $this->getById($id, 'user_id');
    }

    public function addUser($data) {
        return $this->insert($data);
    }

    public function updateUser($id, $data) {
        return $this->update($id, $data, 'user_id');
    }

    public function deleteUser($id) {
    $stmt = $this->conn->prepare("DELETE FROM users WHERE user_id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->rowCount(); // vraća broj obrisanih redova
    }

    public function deleteUserVotes($userId) {
    $stmt = $this->conn->prepare("DELETE FROM votes WHERE user_id = :id");
    $stmt->bindParam(':id', $userId);
    return $stmt->execute();
    }

    public function getAllUsers() {
        return $this->getAll();
    }

    public function getUserByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function resetUserPassword($id, $newPassword) {
        $sql = "UPDATE users SET password = :password WHERE user_id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':password' => $newPassword, ':id' => $id]);
    }

    public function getUserVoteCount($id) {
        $sql = "SELECT COUNT(*) AS total_votes FROM votes WHERE user_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total_votes'] ?? 0;
    }
}
?>
