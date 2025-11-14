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

    public function search($keyword) {
        $sql = "SELECT * FROM contactmessages 
                WHERE full_name LIKE :kw OR email LIKE :kw OR message LIKE :kw";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':kw' => "%$keyword%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByEmail($email) {
        $sql = "SELECT * FROM contactmessages WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function clear() {
        $stmt = $this->conn->prepare("TRUNCATE TABLE contactmessages");
        return $stmt->execute();
    }
}
