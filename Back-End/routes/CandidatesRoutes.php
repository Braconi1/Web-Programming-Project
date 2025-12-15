<?php
require_once __DIR__ . '/../services/CandidatesService.php';

/**
 * @OA\Tag(
 *     name="Candidates",
 *     description="Endpoints for candidate management"
 * )
 */

Flight::group('/candidates', function() {

    $service = new CandidatesService();

    // ---------- GET ALL CANDIDATES ----------
    /**
     * @OA\Get(
     *     path="/candidates",
     *     tags={"Candidates"},
     *     summary="Fetch all candidates",
     *     description="Returns a list of all candidates. JWT token required.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of candidates returned"
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    Flight::route('GET /', function() use ($service) {
        Flight::json($service->getAll());
    });

    // ---------- GET CANDIDATE BY ID ----------
    /**
     * @OA\Get(
     *     path="/candidates/{id}",
     *     tags={"Candidates"},
     *     summary="Fetch single candidate",
     *     description="Returns candidate by ID. JWT token required.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Candidate returned"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    Flight::route('GET /@id', function($id) use ($service) {
        Flight::json($service->getById($id, "candidate_id"));
    });

    // ---------- ADD NEW CANDIDATE ----------
    /**
     * @OA\Post(
     *     path="/candidates",
     *     tags={"Candidates"},
     *     summary="Add new candidate",
     *     description="Creates a new candidate. JWT token required (admin).",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"full_name","position","party_id"},
     *             @OA\Property(property="full_name", type="string", example="Amir Hadžić"),
     *             @OA\Property(property="position", type="string", example="Predsjednik"),
     *             @OA\Property(property="party_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Candidate added"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    Flight::route('POST /', function() use ($service) {
        $data = Flight::request()->data->getData();
        Flight::json($service->add($data));
    });

    // ---------- UPDATE CANDIDATE ----------
    /**
     * @OA\Put(
     *     path="/candidates/{id}",
     *     tags={"Candidates"},
     *     summary="Update a candidate",
     *     description="Updates candidate fields. JWT token required (admin).",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="full_name", type="string"),
     *             @OA\Property(property="position", type="string"),
     *             @OA\Property(property="party_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Candidate updated"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    Flight::route('PUT /@id', function($id) use ($service) {
        $data = Flight::request()->data->getData();
        Flight::json($service->update($id, $data, "candidate_id"));
    });

    // ---------- DELETE CANDIDATE ----------
    /**
     * @OA\Delete(
     *     path="/candidates/{id}",
     *     tags={"Candidates"},
     *     summary="Delete a candidate",
     *     description="Deletes candidate by ID. JWT token required (admin).",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Candidate deleted"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    Flight::route('DELETE /@id', function($id) use ($service) {
        Flight::json($service->delete($id, "candidate_id"));
    });

    // ---------- GET CANDIDATES BY PARTY (public, bez JWT) ----------
    /**
     * @OA\Get(
     *     path="/candidates/party/{partyId}",
     *     tags={"Candidates"},
     *     summary="Get candidates by party",
     *     description="Returns all candidates for a given party. No JWT required.",
     *     @OA\Parameter(
     *         name="partyId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="List of candidates for party")
     * )
     */
    Flight::route('GET /party/@partyId', function($partyId) use ($service) {
    try {
        error_log("Fetching candidates for partyId: " . $partyId); // DEBUG
        $candidates = $service->getByPartyId($partyId);
        Flight::json($candidates);
    } catch (Exception $e) {
        error_log("Error in route: " . $e->getMessage()); // DEBUG
        Flight::json(["error" => $e->getMessage()], 500);
    }
    });


});
