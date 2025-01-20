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
        query trip(\$from: Location!, \$to: Location!, \$arriveBy: Boolean, \$dateTime: DateTime, \$numTripPatterns: Int, \$searchWindow: Int, \$modes: Modes, \$itineraryFiltersDebug: ItineraryFilterDebugProfile, \$wheelchairAccessible: Boolean, \$pageCursor: String) {
          trip(
            from: \$from
            to: \$to
            arriveBy: \$arriveBy
            dateTime: \$dateTime
            numTripPatterns: \$numTripPatterns
            searchWindow: \$searchWindow
            modes: \$modes
            itineraryFilters: {debug: \$itineraryFiltersDebug}
            wheelchairAccessible: \$wheelchairAccessible
            pageCursor: \$pageCursor
          ) {
            previousPageCursor
            nextPageCursor
            tripPatterns {
              aimedStartTime
              aimedEndTime
              expectedEndTime
              expectedStartTime
              duration
              distance
              generalizedCost
              legs {
                id
                mode
                aimedStartTime
                aimedEndTime
                expectedEndTime
                expectedStartTime
                realtime
                distance
                duration
                generalizedCost
                fromPlace {
                  name
                  quay {
                    id
                  }
                }
                toPlace {
                  name
                  quay {
                    id
                  }
                }
                toEstimatedCall {
                  destinationDisplay {
                    frontText
                  }
                }
                line {
                  publicCode
                  name
                  id
                  presentation {
                    colour
                  }
                }
                authority {
                  name
                  id
                }
                pointsOnLink {
                  points
                }
                interchangeTo {
                  staySeated
                }
                interchangeFrom {
                  staySeated
                }
              }
              systemNotices {
                tag
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

        // Vérifier les erreurs dans la réponse GraphQL
        if (isset($responseData['errors'])) {
            error_log('GraphQL Errors: ' . json_encode($responseData['errors']));
            return null;
        }

        // Retourner les données
        return $responseData['data'] ?? null;

    }
}
