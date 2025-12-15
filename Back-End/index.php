<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

require './vendor/autoload.php';
require_once __DIR__ . '/config.php';


require_once __DIR__ . '/middleware/AuthMiddleware.php';
require_once __DIR__ . '/dao/AuthDao.php';

require_once __DIR__ . '/services/UsersService.php';
require_once __DIR__ . '/services/PartiesService.php';
require_once __DIR__ . '/services/CandidatesService.php';
require_once __DIR__ . '/services/VoteService.php';
require_once __DIR__ . '/services/ContactMessageService.php';
require_once __DIR__ . '/services/AuthService.php';

Flight::register('users_service', 'UsersService');
Flight::register('parties_service', 'PartiesService');
Flight::register('candidates_service', 'CandidatesService');
Flight::register('votes_service', 'VoteService');
Flight::register('contact_service', 'ContactMessageService');

Flight::register('auth_service', 'AuthService');
Flight::register('authDao', 'AuthDao');
Flight::register('auth_middleware', 'AuthMiddleware');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

Flight::route('/uploads/@filename', function($filename){
    $path = __DIR__ . '/uploads/' . $filename;
    if(file_exists($path)){
        $mime = mime_content_type($path);
        header('Content-Type: ' . $mime);
        readfile($path);
    } else {
        Flight::halt(404, 'File not found');
    }
});

Flight::route('/*', function() {

    $path = Flight::request()->url;

    if (
        str_contains($path, 'auth/login') ||
        str_contains($path, 'auth/register') ||
        str_contains($path, 'users/login')
    ) {
        return true;
    }

    $authHeader = Flight::request()->getHeader("Authorization");

    if (!$authHeader) {
        Flight::halt(401, "Missing Authorization header");
    }

    $parts = explode(" ", $authHeader);
    $token = $parts[1] ?? null;

    if (!$token) {
        Flight::halt(401, "Invalid Authorization header format");
    }

    Flight::auth_middleware()->verifyToken($token);
});


require_once __DIR__ . '/routes/UsersRoutes.php';
require_once __DIR__ . '/routes/PartiesRoutes.php';
require_once __DIR__ . '/routes/CandidatesRoutes.php';
require_once __DIR__ . '/routes/VoteRoutes.php';
require_once __DIR__ . '/routes/ContactMessagesRoutes.php';
require_once __DIR__ . '/routes/AuthRoutes.php';

Flight::start();
