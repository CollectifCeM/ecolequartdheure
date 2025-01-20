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

        // URL de l'API GraphQL
        $url = 'http://localhost:8080/otp/gtfs/v1';

        // Corps de la requête (JSON)
        $data = [
            "query" => "query stops {
              stops {
                gtfsId
                name
              }
            }",
                        "operationName" => "stops"
                    ];

        // Initialiser cURL
                $ch = curl_init($url);

        // Configurer les options cURL
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Retourner la réponse sous forme de chaîne
                curl_setopt($ch, CURLOPT_POST, true);          // Utiliser la méthode POST
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',          // Type de contenu JSON
                    'OTPTimeout: 180000'                       // En-tête personnalisé pour le délai OTP
                ]);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); // Corps de la requête en JSON

        // Exécuter la requête
                $response = curl_exec($ch);

        // Vérifier les erreurs cURL
                if (curl_errno($ch)) {
                    echo 'Erreur cURL : ' . curl_error($ch);
                    curl_close($ch);
                    exit;
                }

        // Fermer la session cURL
                curl_close($ch);

        // Décoder la réponse JSON
                $responseData = json_decode($response, true);

        // Afficher les résultats
                if ($responseData) {
                    echo "<pre>";
                    print_r($responseData);
                    echo "</pre>";
                } else {
                    echo "Erreur lors de la récupération des données.";
        }

    }
}
