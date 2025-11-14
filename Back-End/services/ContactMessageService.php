<?php
require_once __DIR__ . '/../dao/ContactMessagesDao.php';
require_once 'BaseService.php';

class ContactMessageService extends BaseService {
    public function __construct() {
        parent::__construct(new ContactMessagesDao());
    }

    public function addMessage($data) {
        return $this->dao->insert($data);
    }

    public function getAllMessages() {
        return $this->dao->getAll();
    }

    public function deleteMessage($id) {
        return $this->dao->delete($id, "message_id");
    }

    public function search($keyword) {
        return $this->dao->search($keyword);
    }

    public function getByEmail($email) {
        return $this->dao->getByEmail($email);
    }

    public function clearAll() {
        return $this->dao->clear();
    }
}
