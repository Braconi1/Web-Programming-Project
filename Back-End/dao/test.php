<?php
require_once 'UsersDao.php';
require_once 'PartiesDao.php';
require_once 'CandidatesDao.php';
require_once 'VotesDao.php';
require_once 'ContactMessagesDao.php';

echo "<h2>Testiranje baze</h2>";

$usersDao = new UsersDao();
$partiesDao = new PartiesDao();
$candidatesDao = new CandidatesDao();
$votesDao = new VotesDao();
$messagesDao = new ContactMessagesDao();


echo "<h3>UsersDao</h3>";
if (!$usersDao->getAll()) {
    $usersDao->insert([
        "full_name" => "Student Test",
        "jmbg" => "1234567890123",
        "email" => "student@example.com",
        "password" => "12345"
    ]);
}
print_r($usersDao->getAll());

echo "<hr><h3>PartiesDao</h3>";
print_r($partiesDao->getAll());

echo "<hr><h3>CandidatesDao</h3>";
print_r($candidatesDao->getAll());

echo "<hr><h3>VotesDao</h3>";
$votesDao->insert([
    'user_id' => 1,
    'candidate_id' => 1
]);
print_r($votesDao->getAll());

echo "<hr><h3>ContactMessagesDao</h3>";
$messagesDao->insert([
    'name' => 'Marko',
    'email' => 'marko@test.com',
    'message' => 'Radii Bruda'
]);
print_r($messagesDao->getAll());
?>
