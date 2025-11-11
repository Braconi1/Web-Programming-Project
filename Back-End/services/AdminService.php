<?php
require_once __DIR__ . '/../dao/AdminDao.php';

class AdminService {
    private $dao;

    public function __construct() {
        $this->dao = new AdminDao();
    }

    public function getAll() {
        return $this->dao->getAll();
    }

    public function getById($id) {
        return $this->dao->getById($id);
    }

    public function add($data) {
        return $this->dao->add($data);
    }

    public function update($id, $data) {
        return $this->dao->update($id, $data);
    }

    public function delete($id) {
        return $this->dao->delete($id);
    }
}
?>
