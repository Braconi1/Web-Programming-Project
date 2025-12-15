<?php
require_once __DIR__ . '/../dao/UsersDao.php';
require_once 'BaseService.php';

class UsersService extends BaseService {

    public function __construct() {
        parent::__construct(new UsersDao());
    }

    public function getAllUsers() {
        $users = $this->dao->getAll(); // koristi BaseDao metodu getAll
        return array_map(function($u) {
            return [
                "user_id"   => $u["user_id"],
                "full_name" => $u["full_name"],
                "email"     => $u["email"],
                "role"      => $u["role"]
            ];
        }, $users);
    }

    public function getUserById($id) {
        $user = $this->dao->getById($id, 'user_id'); // BaseDao metoda
        if (!$user) return null;
        unset($user['password']);
        return $user;
    }

    public function registerUser($data) {
        if (!empty($data["password"])) {
            $data["password"] = password_hash($data["password"], PASSWORD_BCRYPT);
        }
        if (isset($data["name"])) {
            $data["full_name"] = $data["name"];
            unset($data["name"]);
        }
        return $this->dao->insert($data); // BaseDao insert
    }

    public function updateUser($id, $data) {
        if (!empty($data["password"])) {
            $data["password"] = password_hash($data["password"], PASSWORD_BCRYPT);
        } else {
            unset($data["password"]);
        }
        if (isset($data["name"])) {
            $data["full_name"] = $data["name"];
            unset($data["name"]);
        }
        return $this->dao->update($id, $data, "user_id"); // BaseDao update
    }

    public function deleteUser($id) {
    // prvo obriši sve glasove korisnika
    $this->dao->deleteUserVotes($id);
    $deletedRows = $this->dao->delete($id, 'user_id');
    return $deletedRows > 0; 
    }

    public function resetUserPassword($id, $newPassword) {
        $data = ['password' => $newPassword];
        return $this->dao->update($id, $data, 'user_id');
    }

    public function loginUser($email, $password) {
        $user = $this->dao->getUserByEmail($email); 
        if (!$user) return ["error" => "Email not found"];
        if (!password_verify($password, $user["password"])) return ["error" => "Incorrect password"];
        unset($user["password"]);
        return $user;
    }
};
?>
