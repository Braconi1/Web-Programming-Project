<?php
require_once __DIR__ . '/../services/ValidationService.php';

/**
 * @OA\Post(
 *     path="/auth/register",
 *     tags={"Auth"},
 *     summary="Register a new user",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email","password","full_name","jmbg"},
 *             @OA\Property(property="full_name", type="string"),
 *             @OA\Property(property="jmbg", type="string"),
 *             @OA\Property(property="email", type="string"),
 *             @OA\Property(property="password", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User registered"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Validation error"
 *     )
 * )
 */
Flight::route('POST /auth/register', function() {
    $data = Flight::request()->data->getData();
    
    // SERVER-SIDE VALIDATION
    $validation = ValidationService::validateUserRegistration($data);
    
    if (!$validation['valid']) {
        Flight::json([
            'error' => 'Validation failed',
            'details' => $validation['errors']
        ], 400);
        return;
    }
    
    // Sanitize input data
    $data = ValidationService::sanitizeData($data);
    $data["role"] = "user"; 
    
    Flight::json(Flight::auth_service()->register($data));
});

/**
 * @OA\Post(
 *     path="/auth/login",
 *     tags={"Auth"},
 *     summary="Login user & return JWT token",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "password"},
 *             @OA\Property(property="email", type="string"),
 *             @OA\Property(property="password", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful login with JWT"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Validation error"
 *     )
 * )
 */
Flight::route('POST /auth/login', function() {
    $data = Flight::request()->data->getData();
    
    // SERVER-SIDE VALIDATION
    $validation = ValidationService::validateUserLogin($data);
    
    if (!$validation['valid']) {
        Flight::json([
            'error' => 'Validation failed',
            'details' => $validation['errors']
        ], 400);
        return;
    }
    
    // Sanitize input data
    $data = ValidationService::sanitizeData($data);
    
    Flight::json(Flight::auth_service()->login($data));
});