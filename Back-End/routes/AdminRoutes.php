<?php
require_once __DIR__ . '/../services/AdminService.php';

/**
 * @OA\Tag(
 *     name="Admins",
 *     description="Routes for managing administrators (fetch, add, update, delete)"
 * )
 */

Flight::group('/admins', function() {

    /**
     * @OA\Get(
     *     path="/admins",
     *     tags={"Admins"},
     *     summary="Get all administrators",
     *     description="Returns a list of all administrators from the database.",
     *     @OA\Response(
     *         response=200,
     *         description="List of all administrators",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="admin_id", type="integer", example=1),
     *                 @OA\Property(property="full_name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="admin@example.com")
     *             )
     *         )
     *     )
     * )
     */
    Flight::route('GET /', function() {
        $service = new AdminService();
        Flight::json($service->getAll());
    });

    /**
     * @OA\Get(
     *     path="/admins/{id}",
     *     tags={"Admins"},
     *     summary="Get admin by ID",
     *     description="Fetches a specific administrator by their ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Administrator ID to retrieve",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Administrator details",
     *         @OA\JsonContent(
     *             @OA\Property(property="admin_id", type="integer", example=1),
     *             @OA\Property(property="full_name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="admin@example.com")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Administrator not found")
     * )
     */
    Flight::route('GET /@id', function($id) {
        $service = new AdminService();
        Flight::json($service->getById($id));
    });

    /**
     * @OA\Post(
     *     path="/admins",
     *     tags={"Admins"},
     *     summary="Add a new administrator",
     *     description="Creates a new administrator record in the database.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"full_name","email","password"},
     *             @OA\Property(property="full_name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="admin@example.com"),
     *             @OA\Property(property="password", type="string", example="admin123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Administrator successfully added"),
     *     @OA\Response(response=400, description="Invalid input data")
     * )
     */
    Flight::route('POST /', function() {
        $data = Flight::request()->data->getData();
        $service = new AdminService();
        Flight::json($service->add($data));
    });

    /**
     * @OA\Put(
     *     path="/admins/{id}",
     *     tags={"Admins"},
     *     summary="Update administrator data",
     *     description="Updates an existing administrator record based on the provided ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Administrator ID to update",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="full_name", type="string", example="John Doe Updated"),
     *             @OA\Property(property="email", type="string", example="newadmin@example.com"),
     *             @OA\Property(property="password", type="string", example="newpassword123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Administrator successfully updated"),
     *     @OA\Response(response=404, description="Administrator not found")
     * )
     */
    Flight::route('PUT /@id', function($id) {
        $data = Flight::request()->data->getData();
        $service = new AdminService();
        Flight::json($service->update($id, $data));
    });

    /**
     * @OA\Delete(
     *     path="/admins/{id}",
     *     tags={"Admins"},
     *     summary="Delete administrator",
     *     description="Deletes an administrator record from the database using their ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Administrator ID to delete",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(response=200, description="Administrator successfully deleted"),
     *     @OA\Response(response=404, description="Administrator not found")
     * )
     */
    Flight::route('DELETE /@id', function($id) {
        $service = new AdminService();
        Flight::json($service->delete($id));
    });
});
?>
