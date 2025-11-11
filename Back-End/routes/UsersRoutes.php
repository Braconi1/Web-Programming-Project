<?php
require_once __DIR__ . '/../services/UsersService.php';

/**
 * @OA\Tag(
 *     name="Users",
 *     description="Endpoints for managing users (registration, login, view, update, delete)"
 * )
 */

Flight::group('/users', function() {

    /**
     * @OA\Get(
     *     path="/users",
     *     tags={"Users"},
     *     summary="Get all users",
     *     description="Returns a list of all registered users from the database.",
     *     @OA\Response(
     *         response=200,
     *         description="List of all users",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="full_name", type="string", example="John Smith"),
     *                 @OA\Property(property="email", type="string", example="john@example.com")
     *             )
     *         )
     *     )
     * )
     */
    Flight::route('GET /', function() {
        $service = new UsersService();
        Flight::json($service->getAllUsers());
    });

    /**
     * @OA\Get(
     *     path="/users/{id}",
     *     tags={"Users"},
     *     summary="Get user by ID",
     *     description="Retrieves user data based on a specific ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID to fetch",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User details found",
     *         @OA\JsonContent(
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="full_name", type="string", example="John Smith"),
     *             @OA\Property(property="email", type="string", example="john@example.com")
     *         )
     *     ),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    Flight::route('GET /@id', function($id) {
        $service = new UsersService();
        Flight::json($service->getUserById($id));
    });

    /**
     * @OA\Post(
     *     path="/users/register",
     *     tags={"Users"},
     *     summary="Register a new user",
     *     description="Creates a new user account in the system.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"full_name","email","password","jmbg"},
     *             @OA\Property(property="full_name", type="string", example="John Smith"),
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="password", type="string", example="password123"),
     *             @OA\Property(property="jmbg", type="string", example="1234567890123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="User successfully registered"),
     *     @OA\Response(response=400, description="Invalid input data")
     * )
     */
    Flight::route('POST /register', function() {
        $data = Flight::request()->data->getData();
        $service = new UsersService();
        Flight::json($service->registerUser($data));
    });

    /**
     * @OA\Post(
     *     path="/users/login",
     *     tags={"Users"},
     *     summary="User login",
     *     description="Logs in a user by verifying email and password credentials.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Login successful"),
     *     @OA\Response(response=401, description="Invalid email or password")
     * )
     */
    Flight::route('POST /login', function() {
        $data = Flight::request()->data->getData();
        $service = new UsersService();
        Flight::json($service->loginUser($data['email'], $data['password']));
    });

    /**
     * @OA\Put(
     *     path="/users/{id}",
     *     tags={"Users"},
     *     summary="Update user information",
     *     description="Allows updating user details such as name or email.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID to update",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", example="newemail@example.com"),
     *             @OA\Property(property="full_name", type="string", example="John Smith Updated")
     *         )
     *     ),
     *     @OA\Response(response=200, description="User successfully updated"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    Flight::route('PUT /@id', function($id) {
        $data = Flight::request()->data->getData();
        $service = new UsersService();
        Flight::json($service->updateUser($id, $data));
    });

    /**
     * @OA\Delete(
     *     path="/users/{id}",
     *     tags={"Users"},
     *     summary="Delete user by ID",
     *     description="Removes a user from the database using their ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID to delete",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(response=200, description="User successfully deleted"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    Flight::route('DELETE /@id', function($id) {
        $service = new UsersService();
        Flight::json($service->deleteUser($id));
    });
});
?>
