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

    /**
     * @OA\Get(
     *     path="/candidates",
     *     tags={"Candidates"},
     *     summary="Get all candidates",
     *     @OA\Response(
     *         response=200,
     *         description="List of all candidates",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     )
     * )
     */
    Flight::route('GET /', function() use ($service) {
        Flight::json($service->getAll());
    });

    /**
     * @OA\Get(
     *     path="/candidates/{id}",
     *     tags={"Candidates"},
     *     summary="Get candidate by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Candidate found"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    Flight::route('GET /@id', function($id) use ($service) {
        Flight::json($service->getById($id, "candidate_id"));
    });

    /**
     * @OA\Post(
     *     path="/candidates",
     *     tags={"Candidates"},
     *     summary="Add new candidate",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"full_name","position","party_id"},
     *             @OA\Property(property="full_name", type="string", example="Amir Hadžić"),
     *             @OA\Property(property="position", type="string", example="Predsjednik"),
     *             @OA\Property(property="party_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Candidate added")
     * )
     */
    Flight::route('POST /', function() use ($service) {
        $data = Flight::request()->data->getData();
        Flight::json($service->add($data));
    });

    /**
     * @OA\Put(
     *     path="/candidates/{id}",
     *     tags={"Candidates"},
     *     summary="Update a candidate",
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
     *     @OA\Response(response=200, description="Candidate updated")
     * )
     */
    Flight::route('PUT /@id', function($id) use ($service) {
        $data = Flight::request()->data->getData();
        Flight::json($service->update($id, $data, "candidate_id"));
    });

    /**
     * @OA\Delete(
     *     path="/candidates/{id}",
     *     tags={"Candidates"},
     *     summary="Delete candidate",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Candidate deleted")
     * )
     */
    Flight::route('DELETE /@id', function($id) use ($service) {
        Flight::json($service->delete($id, "candidate_id"));
    });

});
