<?php
require_once __DIR__ . '/../config/config.php';

class OTPModel {
    private $apiUrl;

    public function __construct() {
        $this->apiUrl = GRAPHQL_API_URL; // URL de l'API GraphQL définie dans config.php
    }

    public function getWalkingTrip($fromLat, $fromLng, $toLat, $toLng) {
        // Construire la requête GraphQL
        $query = <<<GRAPHQL
        query trip(\$from: InputCoordinates!, \$to: InputCoordinates!, \$dateTime: DateTime!) {
          plan(
            from: \$from
            to: \$to
            dateTime: \$dateTime
            modes: ["WALK"]
          ) {
            itineraries {
              duration
              distance
              legs {
                mode
                distance
                duration
                from { name }
                to { name }
              }
            }
          }
        }
        GRAPHQL;

        // Variables à envoyer avec la requête
        $variables = [
            "from" => [
                "coordinates" => [
                    "latitude" => (float) $fromLat,
                    "longitude" => (float) $fromLng
                ]
            ],
            "to" => [
                "coordinates" => [
                    "latitude" => (float) $toLat,
                    "longitude" => (float) $toLng
                ]
            ],
            "dateTime" => date('c') // Date/heure actuelle au format ISO 8601
        ];

        // Exécuter la requête et récupérer les résultats
        return $this->executeGraphQLQuery($query, $variables);
    }

    private function executeGraphQLQuery($query, $variables) {
        // Préparer le payload JSON
        $payload = json_encode([
            "query" => $query,
            "variables" => $variables,
        ]);

        // Initialiser cURL
        $ch = curl_init($this->apiUrl);

        // Configurer les options cURL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload)
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        // Exécuter la requête et capturer la réponse
        $response = curl_exec($ch);

        // Vérifier les erreurs cURL
        if (curl_errno($ch)) {
            error_log('GraphQL cURL Error: ' . curl_error($ch));
            curl_close($ch);
            return null;
        }

        // Fermer cURL
        curl_close($ch);

        // Décoder la réponse JSON
        $responseData = json_decode($response, true);

        var_dump($responseData);

        // Vérifier les erreurs dans la réponse GraphQL
        if (isset($responseData['errors'])) {
            error_log('GraphQL Errors: ' . json_encode($responseData['errors']));
            return null;
        }

        // Retourner les données
        return $responseData['data'] ?? null;
    }
}
