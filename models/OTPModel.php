<?php
require '../vendor/autoload.php';

use GraphQL\Client;
use GraphQL\Query;

class OTPModel {
    private $client;

    public function __construct() {
        $this->client = new Client(GRAPHQL_API_URL, []);
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
        fromPlace {
          name
        }
        toPlace {
          name
        }
        line {
          publicCode
          name
        }
      }
    }
  }
}
GRAPHQL;

        try {
            $response = $this->client->runRawQuery($query, true, [
                'from' => ['lat' => (float) $fromLat, 'lon' => (float) $fromLng],
                'to' => ['lat' => (float) $toLat, 'lon' => (float) $toLng],
                'modes' => 'WALK'
            ]);

            $data = $response->getData();
            return $data['trip']['tripPatterns'] ?? null;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return null;
        }
    }
}
