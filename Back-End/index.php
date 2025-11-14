<?php
require_once __DIR__ . '/flight/Flight.php';

require_once __DIR__ . '/services/UsersService.php';
require_once __DIR__ . '/services/AdminService.php';
require_once __DIR__ . '/services/PartiesService.php';
require_once __DIR__ . '/services/CandidatesService.php';
require_once __DIR__ . '/services/VoteService.php';
require_once __DIR__ . '/services/ContactMessageService.php';
require_once __DIR__ . '/services/DashService.php';

Flight::register('users_service', 'UsersService');
Flight::register('admin_service', 'AdminService');
Flight::register('parties_service', 'PartiesService');
Flight::register('candidates_service', 'CandidatesService');
Flight::register('votes_service', 'VoteService');
Flight::register('contact_service', 'ContactMessageService');
Flight::register('dash_service', 'DashService');

require_once __DIR__ . '/routes/UsersRoutes.php';
require_once __DIR__ . '/routes/AdminRoutes.php';
require_once __DIR__ . '/routes/PartiesRoutes.php';
require_once __DIR__ . '/routes/CandidatesRoutes.php';
require_once __DIR__ . '/routes/VoteRoutes.php';
require_once __DIR__ . '/routes/ContactMessagesRoutes.php';
require_once __DIR__ . '/routes/DashRoutes.php';

$baseUrl = getenv('FLIGHT_BASE_URL') ?: '/ElvirPandur/WEB-PROGRAMMING-PROJECT/Back-End/index.php';
Flight::set('flight.base_url', $baseUrl);

Flight::route('GET /', function() {
    echo "Root radi!";
});
Flight::start();
?>