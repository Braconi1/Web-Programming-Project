<?php
require_once 'BaseDao.php';

class AdminDao extends BaseDao {

    public function __construct() {
        parent::__construct("admins");
    }

    public function getAllAdmins() {
        return $this->getAll();
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

    public function getTotalUsers() {
        $users = new BaseDao("users");
        return $users->countRows();
    }

    public function getTotalCandidates() {
        $cand = new BaseDao("candidates");
        return $cand->countRows();
    }

    public function getTotalVotes() {
        $votes = new BaseDao("votes");
        return $votes->countRows();
    }

    public function getVoteReport() {
        return $this->getVoteDetails();
    }

    public function getAllUsers() {
        $users = new BaseDao("users");
        return $users->getAll();
    }

    public function updateUser($id, $data) {
        $users = new BaseDao("users");
        return $users->update($id, $data, 'user_id');
    }

    public function deleteUser($id) {
        $users = new BaseDao("users");
        return $users->delete($id, 'user_id');
    }
}
?>
