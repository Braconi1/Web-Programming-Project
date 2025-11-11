<?php
require_once __DIR__ . '/../dao/PartiesDao.php';

class PartyService {
    private $dao;

    public function __construct() {
        $this->dao = new PartiesDao();
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
