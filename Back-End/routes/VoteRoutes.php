<?php
require_once __DIR__ . '/../services/VoteService.php';

/**
 * @OA\Tag(
 *     name="Votes",
 *     description="Endpoints for managing votes (view, create, delete)"
 * )
 */

Flight::group('/votes', function() {

    /**
     * @OA\Get(
     *     path="/votes",
     *     tags={"Votes"},
     *     summary="Get all votes",
     *     description="Returns a list of all votes submitted by users.",
     *     @OA\Response(
     *         response=200,
     *         description="List of all votes",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="vote_id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=5),
     *                 @OA\Property(property="candidate_id", type="integer", example=2),
     *                 @OA\Property(property="party_id", type="integer", example=1)
     *             )
     *         )
     *     )
     * )
     */
    Flight::route('GET /', function() {
        $service = new VoteService();
        Flight::json($service->getAll());
    });

    /**
     * @OA\Get(
     *     path="/votes/{id}",
     *     tags={"Votes"},
     *     summary="Get vote by ID",
     *     description="Retrieves details of a single vote using its ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Vote ID to fetch",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vote details found",
     *         @OA\JsonContent(
     *             @OA\Property(property="vote_id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example=5),
     *             @OA\Property(property="candidate_id", type="integer", example=2),
     *             @OA\Property(property="party_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=404, description="Vote not found")
     * )
     */
    Flight::route('GET /@id', function($id) {
        $service = new VoteService();
        Flight::json($service->getById($id));
    });

    /**
     * @OA\Post(
     *     path="/votes",
     *     tags={"Votes"},
     *     summary="Submit a new vote",
     *     description="Creates a new vote record linking a user and candidate.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id","candidate_id","party_id"},
     *             @OA\Property(property="user_id", type="integer", example=5),
     *             @OA\Property(property="candidate_id", type="integer", example=2),
     *             @OA\Property(property="party_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Vote successfully submitted"),
     *     @OA\Response(response=400, description="Invalid or duplicate vote")
     * )
     */
    Flight::route('POST /', function() {
        $data = Flight::request()->data->getData();
        $service = new VoteService();
        Flight::json($service->add($data));
    });

    /**
     * @OA\Delete(
     *     path="/votes/{id}",
     *     tags={"Votes"},
     *     summary="Delete vote by ID",
     *     description="Deletes a specific vote from the database using its ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Vote ID to delete",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(response=200, description="Vote successfully deleted"),
     *     @OA\Response(response=404, description="Vote not found")
     * )
     */
    Flight::route('DELETE /@id', function($id) {
        $service = new VoteService();
        Flight::json($service->delete($id));
    });
});
?>
