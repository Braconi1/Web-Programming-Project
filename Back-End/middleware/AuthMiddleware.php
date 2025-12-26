<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware {

    public function verifyToken() {
        $authHeader = $this->getAuthorizationHeader();

        if (!$authHeader) {
            Flight::halt(401, json_encode(["error" => "Missing Authorization header"]));
        }

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

    public function requireAuthAndRole($requiredRole) {
        $decoded = $this->verifyToken();
        
        if (!isset($decoded->role) || $decoded->role !== $requiredRole) {
            Flight::halt(403, json_encode([
                "error" => "Access denied: Requires " . $requiredRole . " role"
            ]));
        }
        
        return $decoded;
    }

    public function requireAdmin() {
        return $this->requireAuthAndRole('admin');
    }

    public function requireAuth() {
        return $this->verifyToken();
    }

    public function authorizeRole($role) {
        $user = Flight::get('user');
        if (!isset($user->role) || $user->role !== $role) {
            Flight::halt(403, json_encode(["error" => "Access denied: insufficient privileges"]));
        }
    }

    public function authorizeAdmin() {
        $this->authorizeRole('admin');
    }

    public function isAdmin() {
        $user = Flight::get('user');
        return isset($user->role) && $user->role === 'admin';
    }

    public function isUser() {
        $user = Flight::get('user');
        return isset($user->role) && $user->role === 'user';
    }

    public function getUserRole() {
        $user = Flight::get('user');
        return $user->role ?? null;
    }

    public function getUserId() {
        $user = Flight::get('user');
        return $user->id ?? null;
    }

    public function canEditUser($targetUserId) {
        $user = Flight::get('user');
        
        if (!isset($user->id)) {
            return false;
        }
        
        if ($this->isAdmin()) {
            return true;
        }
        
        return $user->id == $targetUserId;
    }

    private function getAuthorizationHeader() {
        $customHeader = Flight::request()->getHeader("X-Authorization");
        if ($customHeader) {
            return trim($customHeader);
        }
        
        $headers = Flight::request()->getHeader("Authorization");
        if ($headers) return trim($headers);
        
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            return trim($_SERVER['HTTP_AUTHORIZATION']);
        }
        
        if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            return trim($_SERVER['REDIRECT_HTTP_AUTHORIZATION']);
        }
        
        if (function_exists('apache_request_headers')) {
            $allHeaders = apache_request_headers();
            if (isset($allHeaders['Authorization'])) {
                return trim($allHeaders['Authorization']);
            }
            if (isset($allHeaders['authorization'])) {
                return trim($allHeaders['authorization']);
            }
        }
        
        return null;
    }

}