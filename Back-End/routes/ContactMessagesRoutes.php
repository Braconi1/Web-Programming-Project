<?php
require_once __DIR__ . '/../services/ContactMessageService.php';

Flight::group('/contact', function() {
    $service = new ContactMessageService();

    Flight::route('GET /', function() use ($service) {
        Flight::json($service->getAll());
    });

    Flight::route('GET /@id', function($id) use ($service) {
        Flight::json($service->getById($id));
    });

    Flight::route('POST /', function() use ($service) {
        $data = Flight::request()->data->getData();
        Flight::json($service->add($data));
    });

    Flight::route('DELETE /@id', function($id) use ($service) {
        Flight::json($service->delete($id));
    });
});
?>
