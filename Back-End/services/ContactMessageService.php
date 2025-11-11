<?php
require_once __DIR__ . '/../dao/ContactMessagesDao.php';

class ContactMessageService {
    private $dao;

    public function __construct() {
        $this->dao = new ContactMessagesDao();
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

    public function delete($id) {
        return $this->dao->delete($id);
    }
}
?>
