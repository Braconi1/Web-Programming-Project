<?php
require_once __DIR__ . '/../services/DashService.php';

/**
 * @OA\Tag(
 *     name="Dashboard",
 *     description="Endpoints for admin dashboard operations (statistics and vote reset)"
 * )
 */

Flight::group('/dashboard', function() {

    /**
     * @OA\Get(
     *     path="/dashboard",
     *     tags={"Dashboard"},
     *     summary="Get dashboard statistics",
     *     description="Returns global voting statistics including total users, candidates, and votes.",
     *     @OA\Response(
     *         response=200,
     *         description="Dashboard statistics successfully fetched",
     *         @OA\JsonContent(
     *             @OA\Property(property="total_users", type="integer", example=120),
     *             @OA\Property(property="total_candidates", type="integer", example=15),
     *             @OA\Property(property="total_votes", type="integer", example=87)
     *         )
     *     )
     * )
     */
    Flight::route('GET /', function() {
        $service = new DashService();
        Flight::json($service->getStats());
    });

    /**
     * @OA\Post(
     *     path="/dashboard/reset",
     *     tags={"Dashboard"},
     *     summary="Reset all votes",
     *     description="Resets all votes in the system to zero. Only accessible to administrators.",
     *     @OA\Response(
     *         response=200,
     *         description="All votes have been reset successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="All votes have been reset successfully!")
     *         )
     *     )
     * )
     */
    Flight::route('POST /reset', function() {
        $service = new DashService();
        $service->resetVotes();
        Flight::json(["message" => "All votes have been reset successfully!"]);
    });
});
?>
