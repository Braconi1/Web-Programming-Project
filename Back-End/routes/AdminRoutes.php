<?php
require_once __DIR__ . '/../services/AdminService.php';

/**
 * @OA\Tag(
 *     name="Admin",
 *     description="Admin management and voting overview endpoints"
 * )
 */

Flight::group('/admin', function() {
    $service = new AdminService();

    /**
     * @OA\Get(
     *     path="/admin",
     *     tags={"Admin"},
     *     summary="Get all admins",
     *     description="Returns a list of all admin users.",
     *     @OA\Response(
     *         response=200,
     *         description="List of all admins",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="admin_id", type="integer", example=1),
     *                 @OA\Property(property="full_name", type="string", example="Elvir Pandur"),
     *                 @OA\Property(property="email", type="string", example="elvir.pandur@stu.ibu.edu.ba")
     *             )
     *         )
     *     )
     * )
     */
    Flight::route('GET /', function() use ($service) {
        Flight::json($service->getAllAdmins());
    });

    /**
     * @OA\Get(
     *     path="/admin/{id}",
     *     tags={"Admin"},
     *     summary="Get admin by ID",
     *     description="Fetch admin details using ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(response=200, description="Admin found"),
     *     @OA\Response(response=404, description="Admin not found")
     * )
     */
    Flight::route('GET /@id', function($id) use ($service) {
        Flight::json($service->getAdminById($id));
    });

    /**
     * @OA\Post(
     *     path="/admin",
     *     tags={"Admin"},
     *     summary="Add a new admin",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"full_name", "email", "password"},
     *             @OA\Property(property="full_name", type="string", example="Elvir Pandur"),
     *             @OA\Property(property="email", type="string", example="elvir.pandur@stu.ibu.edu.ba"),
     *             @OA\Property(property="password", type="string", example="12345")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Admin successfully added")
     * )
     */
    Flight::route('POST /', function() use ($service) {
        $data = Flight::request()->data->getData();
        Flight::json($service->addAdmin($data));
    });

    /**
     * @OA\Put(
     *     path="/admin/{id}",
     *     tags={"Admin"},
     *     summary="Update admin info",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="full_name", type="string", example="Updated Admin Name"),
     *             @OA\Property(property="email", type="string", example="updated@ibu.edu.ba"),
     *             @OA\Property(property="password", type="string", example="newpass123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Admin updated successfully")
     * )
     */
    Flight::route('PUT /@id', function($id) use ($service) {
        $data = Flight::request()->data->getData();
        Flight::json($service->updateAdmin($id, $data));
    });

    /**
     * @OA\Delete(
     *     path="/admin/{id}",
     *     tags={"Admin"},
     *     summary="Delete admin by ID",
     *     description="Deletes an admin record from the system.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=3)
     *     ),
     *     @OA\Response(response=200, description="Admin deleted successfully")
     * )
     */
    Flight::route('DELETE /@id', function($id) use ($service) {
        Flight::json($service->deleteAdmin($id));
    });

    /**
     * @OA\Get(
     *     path="/admin/users",
     *     tags={"Admin"},
     *     summary="Get all users",
     *     description="Returns a list of all registered users.",
     *     @OA\Response(
     *         response=200,
     *         description="List of users",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="user_id", type="integer", example=5),
     *                 @OA\Property(property="full_name", type="string", example="Ajla Selimovic"),
     *                 @OA\Property(property="email", type="string", example="ajla.selimovic@stu.ibu.edu.ba"),
     *                 @OA\Property(property="total_votes", type="integer", example=1)
     *             )
     *         )
     *     )
     * )
     */
    Flight::route('GET /users', function() use ($service) {
        Flight::json($service->getUsers());
    });

    /**
     * @OA\Put(
     *     path="/admin/users/{id}",
     *     tags={"Admin"},
     *     summary="Update user info by admin",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=4)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", example="updateduser@stu.ibu.edu.ba"),
     *             @OA\Property(property="password", type="string", example="newpassword123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="User updated successfully")
     * )
     */
    Flight::route('PUT /users/@id', function($id) use ($service) {
        $data = Flight::request()->data->getData();
        Flight::json($service->updateUser($id, $data));
    });

    /**
     * @OA\Delete(
     *     path="/admin/users/{id}",
     *     tags={"Admin"},
     *     summary="Delete user by admin",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=7)
     *     ),
     *     @OA\Response(response=200, description="User deleted successfully")
     * )
     */
    Flight::route('DELETE /users/@id', function($id) use ($service) {
        Flight::json($service->deleteUser($id));
    });

    /**
     * @OA\Get(
     *     path="/admin/votes-summary",
     *     tags={"Admin"},
     *     summary="View how many votes each user made and for which candidates/parties",
     *     description="Shows user list with number of votes, plus candidates and parties they voted for.",
     *     @OA\Response(
     *         response=200,
     *         description="Vote summary list",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="full_name", type="string", example="Elvir Pandur"),
     *                 @OA\Property(property="total_votes", type="integer", example=1),
     *                 @OA\Property(property="candidate", type="string", example="Amir Hadžić"),
     *                 @OA\Property(property="party", type="string", example="SDA")
     *             )
     *         )
     *     )
     * )
     */
    Flight::route('GET /votes-summary', function() use ($service) {
        Flight::json($service->getReport());
    });
});
?>
