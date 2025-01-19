<?php
require_once __DIR__ . '/../models/OTPModel.php';

class ItineraryController {
    public static function showForm() {
        include __DIR__ . '/../views/form.php';
    }

    public static function calculateItinerary() {
        if (!isset($_POST['from_lat']) || !isset($_POST['from_lng']) || !isset($_POST['to_lat']) || !isset($_POST['to_lng'])) {
            header('Location: /');
            exit();
        }

        $fromLat = $_POST['from_lat'];
        $fromLng = $_POST['from_lng'];
        $toLat = $_POST['to_lat'];
        $toLng = $_POST['to_lng'];

        $model = new OTPModel();
        $tripPatterns = $model->getWalkingTrip($fromLat, $fromLng, $toLat, $toLng);

        include __DIR__ . '/../views/result.php';
    }
}
