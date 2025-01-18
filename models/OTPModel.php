<?php

require_once __DIR__ . '/../config/config.php';
class OTPModel {
    public static function getWalkingItinerary($fromLat, $fromLng, $toLat, $toLng) {
        $url = OTP_API_URL . "?fromPlace={$fromLat},{$fromLng}&toPlace={$toLat},{$toLng}&mode=WALK";

        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if (isset($data['plan']['itineraries'][0])) {
            $itinerary = $data['plan']['itineraries'][0];
            return [
                'duration' => $itinerary['duration'], // Durée en secondes
                'distance' => $itinerary['legs'][0]['distance'], // Distance en mètres
                'steps' => $itinerary['legs'][0]['steps'] // Étapes
            ];
        } else {
            return null; // Aucun itinéraire trouvé
        }
    }
}
