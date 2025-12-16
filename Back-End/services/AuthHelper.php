<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function getUserIdFromToken($token) {
    try {
        $decoded = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));
        // U tvom login payloadu user_id je pod "id"
        return $decoded->id ?? null;
    } catch (Exception $e) {
        return null;
    }
}
