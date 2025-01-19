<?php
require_once __DIR__ . '/../vendor/autoload.php';

class OTPModel {
    private $apiUrl;

    public function __construct() {
        $this->apiUrl = GRAPHQL_API_URL;
    }

    public function getWalkingTrip($fromLat, $fromLng, $toLat, $toLng) {
        $query = <<<GRAPHQL
        query trip($from: Location!, $to: Location!, $arriveBy: Boolean, $dateTime: DateTime, $numTripPatterns: Int, $searchWindow: Int, $modes: Modes, $itineraryFiltersDebug: ItineraryFilterDebugProfile, $wheelchairAccessible: Boolean, $pageCursor: String) {
          trip(
            from: $from
            to: $to
            arriveBy: $arriveBy
            dateTime: $dateTime
              numTripPatterns: $numTripPatterns
            searchWindow: $searchWindow
            modes: $modes
            itineraryFilters: {debug: $itineraryFiltersDebug}
            wheelchairAccessible: $wheelchairAccessible
            pageCursor: $pageCursor
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

        $variables = [
            'from' => ['lat' => (float)$fromLat, 'lon' => (float)$fromLng],
            'to' => ['lat' => (float)$toLat, 'lon' => (float)$toLng],
            'modes' => 'WALK',
        ];

        return $this->executeGraphQLQuery($query, $variables);
    }

    private function executeGraphQLQuery($query, $variables) {
        $payload = json_encode([
            'query' => $query,
            'variables' => $variables,
        ]);

        $ch = curl_init($this->apiUrl);
        var_dump($this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload)
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        var_dump($ch);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            error_log('GraphQL CURL Error: ' . curl_error($ch));
            return null;
        }

        curl_close($ch);

        return json_decode($response, true);
    }
}
