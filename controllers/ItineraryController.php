<?php
require_once 'models/OTPModel.php';

class ItineraryController {
    public static function showForm() {
        include 'views/form.php';
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

        $result = OTPModel::getWalkingItinerary($fromLat, $fromLng, $toLat, $toLng);

        include 'views/result.php';
    }
}
