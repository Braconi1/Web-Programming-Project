<?php
require_once __DIR__ . '/../services/PartiesService.php';

/**
 * @OA\Tag(
 *     name="Parties",
 *     description="Endpoints for political parties"
 * )
 */

Flight::group('/parties', function() {

    $service = new PartiesService();

    /**
     * @OA\Get(
     *     path="/parties",
     *     tags={"Parties"},
     *     summary="Get all parties",
     *     @OA\Response(
     *         response=200,
     *         description="List of parties",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     )
     * )
     */
    Flight::route('GET /', function() use ($service) {
        Flight::json($service->getAll());
    });

    /**
     * @OA\Post(
     *     path="/parties",
     *     tags={"Parties"},
     *     summary="Add new party",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"party_name"},
     *             @OA\Property(property="party_name", type="string", example="SDA"),
     *             @OA\Property(property="logo", type="string", example="sda.png")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Party added"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    Flight::route('POST /', function() use ($service) {
        Flight::auth_middleware()->requireAdmin();
        $data = Flight::request()->data->getData();
        Flight::json($service->addParty($data));
    });

    /**
     * @OA\Put(
     *     path="/parties/{id}",
     *     tags={"Parties"},
     *     summary="Update party",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the party",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="party_name", type="string", example="Updated Party"),
     *             @OA\Property(property="logo", type="string", example="updated_logo.png")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Party updated"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    Flight::route('PUT /@id', function($id) use ($service) {
        Flight::auth_middleware()->requireAdmin();
        $data = Flight::request()->data->getData();
        Flight::json($service->updateParty($id, $data));
    });

    /**
     * @OA\Delete(
     *     path="/parties/{id}",
     *     tags={"Parties"},
     *     summary="Delete party",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the party to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Party deleted"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    Flight::route('DELETE /@id', function($id) use ($service) {
        Flight::auth_middleware()->requireAdmin();
        Flight::json($service->deleteParty($id));
    });

});