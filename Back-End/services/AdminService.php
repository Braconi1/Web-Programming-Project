<?php
require_once __DIR__ . '/../dao/AdminDao.php';

class AdminService {
    private $dao;

    public function __construct() {
        $this->dao = new AdminDao();
    }

    public function getAllAdmins() {
        return $this->dao->getAllAdmins();
    }

    public function getAdminById($id) {
        return $this->dao->getAdminById($id);
    }

    public function addAdmin($data) {
        if (empty($data['full_name']) || empty($data['email']) || empty($data['password'])) {
            return ["error" => "Missing required fields"];
        }
        return $this->dao->addAdmin($data);
    }

    public function updateAdmin($id, $data) {
        return $this->dao->updateAdmin($id, $data);
    }

    public function deleteAdmin($id) {
        return $this->dao->deleteAdmin($id);
    }

    public function getVoteStats() {
        return [
            "users" => $this->dao->getTotalUsers(),
            "candidates" => $this->dao->getTotalCandidates(),
            "votes" => $this->dao->getTotalVotes()
        ];
    }

    public function getReport() {
        return $this->dao->getVoteReport();
    }

    public function getUsers() {
        return $this->dao->getAllUsers();
    }

    public function updateUser($id, $data) {
        return $this->dao->updateUser($id, $data);
    }

    public function deleteUser($id) {
        return $this->dao->deleteUser($id);
    }
}
?>
