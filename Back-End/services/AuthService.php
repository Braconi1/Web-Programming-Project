<?php
use Firebase\JWT\JWT;

class AuthService {

    private $dao;

    public function __construct() {
        $this->dao = new AuthDao();
    }

    public function register($data) {
        if (isset($data["password"])) {
            $data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);
        }
        if (isset($data["name"])) {
            $data["full_name"] = $data["name"];
            unset($data["name"]);
        }
        return $this->dao->insert($data);
    }

    public function login($data) {
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        $user = $this->dao->get_user_by_email($email);
        if (!$user) return ["error" => "Email not found"];
        if (!password_verify($password, $user["password"])) return ["error" => "Wrong password"];

        $payload = [
            "id"    => $user["user_id"],
            "email" => $user["email"],
            "role"  => $user["role"] ?? "user",
            "exp"   => time() + 3600
        ];

        $token = JWT::encode($payload, Config::JWT_SECRET(), 'HS256');

        return [
            "token" => $token,
            "user"  => [
                "user_id"   => $user["user_id"],
                "email"     => $user["email"],
                "full_name" => $user["full_name"],
                "role"      => $user["role"] ?? "user"
            ]
        ];
    }
}
