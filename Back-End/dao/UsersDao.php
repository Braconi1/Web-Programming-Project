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
        return $this->delete($id, 'user_id');
    }

    public function getAllUsers() {
        return $this->getAll();
    }

    public function getUserByEmail($email) {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function resetUserPassword($id, $newPassword) {
        $sql = "UPDATE users SET password = :password WHERE user_id = :id";
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute([':password' => $newPassword, ':id' => $id]);
    }

    public function getUserVoteCount($id) {
        $sql = "SELECT COUNT(*) AS total_votes FROM votes WHERE user_id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total_votes'] ?? 0;
    }
}
?>
