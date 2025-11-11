<?php
require_once __DIR__ . '/../dao/UsersDao.php';

class UsersService {
    private $usersDao;

    public function __construct() {
        $this->usersDao = new UsersDao();
    }

    // Registracija new usera
    public function registerUser($data) {
        if (empty($data['full_name']) || empty($data['email']) || empty($data['password']) || empty($data['jmbg'])) {
            throw new Exception("All fields are required!");
        }

        // Slike
        if (strlen($data['jmbg']) != 13) {
            throw new Exception("JMBG must be exactly 13 characters.");
        }

        // email provjera
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }
        
        $existingUser = $this->usersDao->getUserByEmail($data['email']);
        if ($existingUser) {
            throw new Exception("Email already registered!");
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        return $this->usersDao->createUser($data);
    }

    // Login
    public function loginUser($email, $password) {
        $user = $this->usersDao->getUserByEmail($email);
        if (!$user || !password_verify($password, $user['password'])) {
            throw new Exception("Invalid email or password.");
        }

        return $user;
    }

    public function getAllUsers() {
        return $this->usersDao->getAllUsers();
    }

    public function getUserById($id) {
        $user = $this->usersDao->getUserById($id);
        if (!$user) {
            throw new Exception("User not found.");
        }
        return $user;
    }

    // Update users
    public function updateUser($id, $data) {
        if (!isset($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }
        return $this->usersDao->updateUser($id, $data);
    }

    public function deleteUser($id) {
        return $this->usersDao->deleteUser($id);
    }
}
?>
