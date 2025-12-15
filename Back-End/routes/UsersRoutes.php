<?php


/**
 * @OA\Tag(
 *     name="Users",
 *     description="User authentication and management"
 * )
 */

//
// ============================================
//  PUBLIC ENDPOINTS (NO JWT REQUIRED)
// ============================================
//

// ---------- LOGIN USER ----------
/**
 * @OA\Post(
 *     path="/users/login",
 *     tags={"Users"},
 *     summary="Login user",
 *     description="Logs in a user using email/password and returns JWT token.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email","password"},
 *             @OA\Property(property="email", type="string", example="admin@gmail.com"),
 *             @OA\Property(property="password", type="string", example="admin123")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Login successful"),
 *     @OA\Response(response=401, description="Invalid credentials")
 * )
 */
Flight::route("POST /users/login", function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::auth_service()->login($data));
});

// ---------- REGISTER USER ----------
/**
 * @OA\Post(
 *     path="/users/register",
 *     tags={"Users"},
 *     summary="Register new user",
 *     description="Registers a new user with role 'user'.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"full_name","email","password"},
 *             @OA\Property(property="full_name", type="string", example="John Doe"),
 *             @OA\Property(property="email", type="string", example="john@example.com"),
 *             @OA\Property(property="password", type="string", example="mypassword")
 *         )
 *     ),
 *     @OA\Response(response=201, description="User registered successfully"),
 *     @OA\Response(response=400, description="Validation error")
 * )
 */
Flight::route("POST /users/register", function() {
    $data = Flight::request()->data->getData();
    $data["role"] = "user"; 
    Flight::json(Flight::users_service()->registerUser($data));
});

//
// ============================================
//  PROTECTED USER MANAGEMENT (JWT REQUIRED)
// ============================================
//

Flight::group('/users', function () {

    // ---------- GET ALL USERS ----------
    /**
     * @OA\Get(
     *     path="/users",
     *     tags={"Users"},
     *     summary="Fetch all users",
     *     description="Returns all users. Admin only.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Users fetched"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    Flight::route('GET /', function () {
        $decoded = Flight::auth_middleware()->verifyToken();
        Flight::auth_middleware()->authorizeRole('admin');
        Flight::json(Flight::users_service()->getAllUsers());
    });

    // ---------- GET USER BY ID ----------
    /**
     * @OA\Get(
     *     path="/users/{id}",
     *     tags={"Users"},
     *     summary="Fetch single user",
     *     description="Returns user by ID. JWT token required.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="User returned"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    Flight::route('GET /@id', function($id) {
        $decoded = Flight::auth_middleware()->verifyToken();
        if ($decoded->id != $id) {
            Flight::auth_middleware()->authorizeRole('admin');
        }
        Flight::json(Flight::users_service()->getUserById($id));
    });

    // ---------- UPDATE USER ----------
    /**
     * @OA\Put(
     *     path="/users/{id}",
     *     tags={"Users"},
     *     summary="Update user",
     *     description="Updates user fields (full_name, email, role, password...). JWT required.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="full_name", type="string", example="Elvir Pandur"),
     *             @OA\Property(property="email", type="string", example="test@gmail.com"),
     *             @OA\Property(property="password", type="string", example="123"),
     *             @OA\Property(property="role", type="string", example="admin")
     *         )
     *     ),
     *     @OA\Response(response=200, description="User updated"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    Flight::route('PUT /@id', function($id) {
        $decoded = Flight::auth_middleware()->verifyToken();
        if ($decoded->id != $id) Flight::auth_middleware()->authorizeRole('admin');
        $data = Flight::request()->data->getData();
        Flight::json(Flight::users_service()->updateUser($id, $data));
    });

    // ---------- RESET PASSWORD ----------
    /**
     * @OA\Put(
     *     path="/users/{id}/reset-password",
     *     tags={"Users"},
     *     summary="Reset user password",
     *     description="Resets a user's password. Admin only. JWT required.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="password", type="string", example="newPassword123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Password reset successful"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    Flight::route('PUT /@id/reset-password', function($id) {
        $decoded = Flight::auth_middleware()->verifyToken();
        Flight::auth_middleware()->authorizeRole('admin');

        $data = Flight::request()->data->getData();
        if (!isset($data['password']) || empty($data['password'])) {
            Flight::halt(400, json_encode(["error" => "Password is required"]));
        }

        $hashed = password_hash($data['password'], PASSWORD_BCRYPT);
        $success = Flight::users_service()->resetUserPassword($id, $hashed);

        if ($success) {
            Flight::json(["message" => "Password reset successful"]);
        } else {
            Flight::halt(500, json_encode(["error" => "Failed to reset password"]));
        }
    });

    // ---------- DELETE USER ----------
    /**
     * @OA\Delete(
     *     path="/users/{id}",
     *     tags={"Users"},
     *     summary="Delete user",
     *     description="Deletes a user by ID. Admin only.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="User deleted"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    Flight::route('DELETE /@id', function($id) {
    $decoded = Flight::auth_middleware()->verifyToken();
    Flight::auth_middleware()->authorizeRole('admin');

    $success = Flight::users_service()->deleteUser($id);
    if ($success) {
        Flight::json(["message" => "User deleted successfully"]);
    } else {
        Flight::halt(404, json_encode(["error" => "User not found"]));
    }
});



});