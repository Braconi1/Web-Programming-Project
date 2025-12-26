<?php
require_once __DIR__ . '/../services/ContactMessageService.php';

/**
 * @OA\Tag(
 *     name="Contact Messages",
 *     description="Contact form management"
 * )
 */

Flight::group('/messages', function() {

    $service = new ContactMessageService();

    /**
     * @OA\Post(
     *     path="/messages",
     *     tags={"Contact Messages"},
     *     summary="Send a contact message",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","message"},
     *             @OA\Property(property="name", type="string", example="Marko"),
     *             @OA\Property(property="email", type="string", example="marko@test.com"),
     *             @OA\Property(property="message", type="string", example="I have a question...")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Message stored")
     * )
     */
    Flight::route('POST /', function() use ($service) {
        $data = Flight::request()->data->getData();
        Flight::json($service->addMessage($data));
    });

    /**
     * @OA\Get(
     *     path="/messages",
     *     tags={"Contact Messages"},
     *     summary="Get all contact messages",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of messages",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    Flight::route('GET /', function() use ($service) {
        Flight::auth_middleware()->requireAdmin();
        Flight::json($service->getAllMessages());
    });

    /**
     * @OA\Delete(
     *     path="/messages/{id}",
     *     tags={"Contact Messages"},
     *     summary="Delete a message",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the message",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Message deleted"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    Flight::route('DELETE /@id', function($id) use ($service) {
        Flight::auth_middleware()->requireAdmin();
        Flight::json($service->deleteMessage($id));
    });
});