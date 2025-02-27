<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../controllers/ItineraryController.php';

$requestUri = $_SERVER['REQUEST_URI'];

if ($requestUri === '/' || $requestUri === '/index.php') {
    ItineraryController::showForm();
} elseif ($requestUri === '/calculate' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    ItineraryController::calculateItinerary();
} else {
    http_response_code(404);
    echo "Page non trouvée.";
}
