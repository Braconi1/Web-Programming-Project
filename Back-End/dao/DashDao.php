<?php
require_once 'UsersDao.php';
require_once 'CandidatesDao.php';
require_once 'VotesDao.php';

$usersDao = new UsersDao();
$candidatesDao = new CandidatesDao();
$votesDao = new VotesDao();

if (isset($_POST['resetVotes'])) {
    $votesDao->resetVotes();
    echo json_encode(["message" => "All votes have been reset successfully!"]);
    exit;
}

$response = [
    "totalUsers" => count($usersDao->getAll()),
    "totalCandidates" => count($candidatesDao->getAll()),
    "totalVotes" => count($votesDao->getAll())
];

header('Content-Type: application/json');
echo json_encode($response);
?>
