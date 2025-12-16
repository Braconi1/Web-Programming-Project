<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware {

    public function verifyToken() {
        $authHeader = $this->getAuthorizationHeader();

        if (!$authHeader) {
            Flight::halt(401, json_encode(["error" => "Missing Authorization header"]));
        }

        // Očekuje se format: Bearer <token>
        if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            Flight::halt(401, json_encode(["error" => "Invalid Authorization header format"]));
        }

        $token = $matches[1];

        try {
            $decoded_token = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));
        } catch (Exception $e) {
            Flight::halt(401, json_encode(["error" => "Invalid or expired token"]));
        }

        Flight::set('user', $decoded_token);
        return $decoded_token;
    }


    public function authorizeRole($role) {
        $user = Flight::get('user');
        if (!isset($user->role) || $user->role !== $role) {
            Flight::halt(403, 'Access denied: insufficient privileges');
        }
    }

    public function authorizeRoles(array $roles) {
        $user = Flight::get('user');
        if (!isset($user->role) || !in_array($user->role, $roles)) {
            Flight::halt(403, 'Access denied: role not allowed');
        }
    }

    private function getAuthorizationHeader() {
        $customHeader = Flight::request()->getHeader("X-Authorization");
    if ($customHeader) {
        error_log("Found X-Authorization: " . $customHeader);
        return trim($customHeader);
    }
    
    // Originalni pokušaji...
    $headers = Flight::request()->getHeader("Authorization");
    if ($headers) return trim($headers);
    
    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        return trim($_SERVER['HTTP_AUTHORIZATION']);
    }
    
    if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
        return trim($_SERVER['REDIRECT_HTTP_AUTHORIZATION']);
    }
    
    // Apache specific
    if (function_exists('apache_request_headers')) {
        $allHeaders = apache_request_headers();
        if (isset($allHeaders['Authorization'])) {
            return trim($allHeaders['Authorization']);
        }
        if (isset($allHeaders['authorization'])) {
            return trim($allHeaders['authorization']);
        }
    }
    
    // Debug
    error_log("NO AUTH HEADER FOUND!");
    error_log("Available headers: " . print_r(getallheaders(), true));
    
    return null;
    }

}
