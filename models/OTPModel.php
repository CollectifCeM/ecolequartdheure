<?php
require_once __DIR__ . '/../vendor/autoload.php';

use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use GraphQL\Language\Parser;
use GraphQL\Executor\ExecutionResult;

class OTPModel {
    private $apiUrl;

    public function __construct() {
        $this->apiUrl = GRAPHQL_API_URL;
    }

    public function getWalkingTrip($fromLat, $fromLng, $toLat, $toLng) {
        $query = <<<GRAPHQL
        query trip(\$from: Location!, \$to: Location!, \$modes: Modes) {
          trip(from: \$from, to: \$to, modes: \$modes) {
            tripPatterns {
              aimedStartTime
              aimedEndTime
              duration
              distance
              legs {
                mode
                distance
                duration
                fromPlace { name }
                toPlace { name }
                line { publicCode, name }
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

        $response = $this->executeGraphQLQuery($query, $variables);

        if (isset($response['data']['trip']['tripPatterns'])) {
            return $response['data']['trip']['tripPatterns'];
        } else {
            return null; // Erreur ou données manquantes
        }
    }

    private function executeGraphQLQuery($query, $variables) {
        $payload = json_encode([
            'query' => $query,
            'variables' => $variables,
        ]);

        $ch = curl_init($this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload)
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            error_log('GraphQL CURL Error: ' . curl_error($ch));
            return null;
        }

        curl_close($ch);

        return json_decode($response, true);
    }
}
