<?php
require_once 'BaseDao.php';

class ContactMessagesDao extends BaseDao {

    public function __construct() {
        parent::__construct("contactmessages");
    }

    public function getMessageById($id) {
        return $this->getById($id, 'message_id');
    }

    public function addMessage($data) {
        return $this->insert($data);
    }

    public function updateMessage($id, $data) {
        return $this->update($id, $data, 'message_id');
    }

    public function deleteMessage($id) {
        return $this->delete($id, 'message_id');
    }

    public function getAllMessages() {
        return $this->getAll();
    }
}
?>
