<?php
require_once 'BaseDao.php';

class UsersDao extends BaseDao {
    public function __construct() {
        parent::__construct('Users');
    }

    public function getByEmail($email) {
        $stmt = $this->connection->prepare("SELECT * FROM Users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createAccount($data) {
        if (empty($data['full_name']) || empty($data['jmbg']) || empty($data['email']) || empty($data['password'])) {
            throw new Exception("All fields are required");
        }

        if ($this->getByEmail($data['email'])) {
            throw new Exception("Email already exists");
        }

        return $this->insert($data);
    }
}
?>
