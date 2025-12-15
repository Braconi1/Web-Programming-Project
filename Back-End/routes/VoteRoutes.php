<?php
require_once __DIR__ . '/../services/VoteService.php';
require_once __DIR__ . '/../services/AuthHelper.php';


/**
 * @OA\Tag(
 *     name="Votes",
 *     description="Endpoints for managing votes (view, create, delete)"
 * )
 */

Flight::group('/votes', function() {

    $service = new VoteService();

    /**
     * @OA\Get(
     *     path="/votes",
     *     tags={"Votes"},
     *     summary="Get all votes",
     *     description="Returns a list of all votes submitted by users, including candidate's party information.",
     *     @OA\Response(
     *         response=200,
     *         description="List of all votes",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="vote_id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="candidate_id", type="integer", example=2),
     *                 @OA\Property(property="party_id", type="integer", example=1),
     *                 @OA\Property(property="party_name", type="string", example="SDA")
     *             )
     *         )
     *     )
     * )
     */
    Flight::route('GET /', function() use ($service) {
        Flight::json($service->getReport());
    });

    /**
     * @OA\Get(
     *     path="/votes/{id}",
     *     tags={"Votes"},
     *     summary="Get vote by ID",
     *     description="Retrieves details of a single vote using its ID, including candidate's party information.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Vote ID to fetch",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vote found",
     *         @OA\JsonContent(
     *             @OA\Property(property="vote_id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="candidate_id", type="integer", example=2),
     *             @OA\Property(property="party_id", type="integer", example=1),
     *             @OA\Property(property="party_name", type="string", example="SDA")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Vote not found")
     * )
     */
    Flight::route('GET /@id', function($id) use ($service) {
        Flight::json($service->getById($id));
    });

    /**
     * @OA\Post(
     *     path="/votes",
     *     tags={"Votes"},
     *     summary="Submit a new vote",
     *     description="Creates a new vote record linking a user and candidate. Party info is inferred from candidate.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "candidate_id"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="candidate_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Vote successfully submitted"),
     *     @OA\Response(response=400, description="Duplicate vote or invalid data")
     * )
     */
    Flight::route('POST /', function() use ($service) {
    $userId = getUserIdFromRequest();
    $data = Flight::request()->data->getData();

    if (!isset($data['candidate_id'])) {
        Flight::halt(400, json_encode(['error' => 'Candidate ID required']));
    }

    if ($service->hasVoted($userId)) {
        Flight::json([
            'error' => 'You have already voted'
        ], 400);    
        return;
    }

    Flight::json($service->addVote([
        'user_id' => $userId,
        'candidate_id' => $data['candidate_id']
    ]));
});

// helper funkcija
function getUserIdFromRequest() {
    $headers = getallheaders();
    if (!isset($headers['Authorization'])) {
        Flight::halt(401, json_encode(['error' => 'Unauthorized']));
    }
    $token = str_replace('Bearer ', '', $headers['Authorization']);
    $userId = getUserIdFromToken($token);
    if (!$userId) Flight::halt(401, json_encode(['error' => 'Invalid token']));
    return $userId;
}



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
     *     @OA\Response(response=200, description="Vote deleted"),
     *     @OA\Response(response=404, description="Vote not found")
     * )
     */
    Flight::route('DELETE /@id', function($id) use ($service) {
        Flight::json($service->delete($id));
    });

    /**
     * @OA\Get(
     *     path="/votes/report",
     *     tags={"Votes"},
     *     summary="Get voting report",
     *     description="Returns a report showing for each user which candidate they voted for, candidate's party, and total votes for that candidate.",
     *     @OA\Response(
     *         response=200,
     *         description="Voting report",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="user_name", type="string", example="Elvir Pandur"),
     *                 @OA\Property(property="candidate_id", type="integer", example=2),
     *                 @OA\Property(property="candidate_name", type="string", example="Emina Kovačević"),
     *                 @OA\Property(property="position", type="string", example="Zamjenik"),
     *                 @OA\Property(property="party_id", type="integer", example=2),
     *                 @OA\Property(property="party_name", type="string", example="BPS"),
     *                 @OA\Property(property="total_votes_for_candidate", type="integer", example=3)
     *             )
     *         )
     *     )
     * )
     */
    Flight::route('GET /report', function() use ($service) {
        Flight::json($service->getVotingReport());
    });


    /**
     * @OA\Get(
     *     path="/votes/candidate/{id}/count",
     *     tags={"Votes"},
     *     summary="Get total votes for a candidate",
     *     description="Returns the total number of votes a specific candidate has received.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Candidate ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Total votes for candidate",
     *         @OA\JsonContent(
     *             @OA\Property(property="candidate_id", type="integer", example=1),
     *             @OA\Property(property="total_votes", type="integer", example=10)
     *         )
     *     )
     * )
     */
    Flight::route('GET /user/@id/count', function($id) use ($service) {
    $total = $service->hasVoted($id) ? $service->countVotesByUser($id) : 0;
    Flight::json(['user_id' => $id, 'total_votes' => $total]);
    });


    // --- Broj glasova koje je korisnik dao ---
    /**
     * @OA\Get(
     *     path="/votes/user/{id}/count",
     *     tags={"Votes"},
     *     summary="Get total votes by a user",
     *     description="Returns the total number of votes a specific user has submitted.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Total votes by user",
     *         @OA\JsonContent(
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="total_votes", type="integer", example=3)
     *         )
     *     )
     * )
     */
    Flight::route('GET /user/@id/count', function($id) use ($service) {
        $total = $service->hasVoted($id) ? $service->dao->countVotesByUser($id) : 0;
        Flight::json(['user_id' => $id, 'total_votes' => $total]);
    });

});
?>
