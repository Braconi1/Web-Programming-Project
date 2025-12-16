<?php

/**
 * @OA\Post(
 *     path="/auth/register",
 *     tags={"Auth"},
 *     summary="Register a new user",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id","email","password","full_name","surname","jmbg"},
 *             @OA\Property(property="user_id", type="integer", example=10),
 *             @OA\Property(property="full_name", type="string"),
 *             @OA\Property(property="jmbg", type="string"),
 *             @OA\Property(property="email", type="string"),
 *             @OA\Property(property="password", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User registered"
 *     )
 * )
 */
Flight::route('POST /auth/register', function() {
    $data = Flight::request()->data->getData();
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
 *     )
 * )
 */
Flight::route('POST /auth/login', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::auth_service()->login($data));
});
