<?php
require_once 'BaseDao.php';

class AdminDao extends BaseDao {

    public function __construct() {
        parent::__construct("admins");
    }

    public function getAdminById($id) {
        return $this->getById($id, 'admin_id');
    }

    public function addAdmin($data) {
        return $this->insert($data);
    }

    public function updateAdmin($id, $data) {
        return $this->update($id, $data, 'admin_id');
    }

    public function deleteAdmin($id) {
        return $this->delete($id, 'admin_id');
    }

    public function getAllAdmins() {
        return $this->getAll();
    }

    public function getAdminByUsernameAndPassword($username, $password) {
        $stmt = $this->connection->prepare("SELECT * FROM admins WHERE username = :username AND password = :password");
        $stmt->execute([':username' => $username, ':password' => $password]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
