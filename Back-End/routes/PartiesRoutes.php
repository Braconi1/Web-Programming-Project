<?php
require_once __DIR__ . '/../services/UsersService.php';



Flight::group('/users', function() {

    Flight::route('GET /', function() {
        $service = new UsersService();
        Flight::json($service->getAllUsers());
    });

    Flight::route('GET /@id', function($id) {
        $service = new UsersService();
        Flight::json($service->getUserById($id));
    });


    Flight::route('POST /register', function() {
        $data = Flight::request()->data->getData();
        $service = new UsersService();
        Flight::json($service->registerUser($data));
    });


    Flight::route('POST /login', function() {
        $data = Flight::request()->data->getData();
        $service = new UsersService();
        Flight::json($service->loginUser($data['email'], $data['password']));
    });


    Flight::route('PUT /@id', function($id) {
        $data = Flight::request()->data->getData();
        $service = new UsersService();
        Flight::json($service->updateUser($id, $data));
    });

    Flight::route('DELETE /@id', function($id) {
        $service = new UsersService();
        Flight::json($service->deleteUser($id));
    });
});
?>
