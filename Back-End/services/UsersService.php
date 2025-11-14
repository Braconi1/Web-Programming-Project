<?php
require_once __DIR__ . '/../dao/UsersDao.php';
require_once 'BaseService.php';

class UsersService extends BaseService {

    public function __construct() {
        parent::__construct(new UsersDao());
    }

    public function getAllUsers() {
        return $this->dao->getAll();
    }

    public function getUserById($id) {
        return $this->dao->getById($id, "user_id");
    }

    public function registerUser($data) {
        return $this->dao->insert($data);
    }

    public function loginUser($email, $password) {
        $user = $this->dao->getUserByEmail($email);
        if (!$user) return ["error" => "Email not found"];
        if ($user["password"] !== $password) return ["error" => "Incorrect password"];
        return $user;
    }

    public function updateUser($id, $data) {
        return $this->dao->update($id, $data, "user_id");
    }

    public function deleteUser($id) {
        return $this->dao->delete($id, "user_id");
    }
}
