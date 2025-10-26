<?php
require_once 'BaseDao.php';

class ContactMessagesDao extends BaseDao {
    public function __construct() {
        parent::__construct('ContactMessages');
    }

    public function getByEmail($email) {
        $stmt = $this->connection->prepare("SELECT * FROM ContactMessages WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
