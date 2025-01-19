<?php
require 'vendor/autoload.php';

use GraphQL\Client;
use GraphQL\Exception\QueryError;
use GraphQL\QueryBuilder\QueryBuilder;

class OTPModel {
    private $client;

    public function __construct() {
        $this->client = new Client(GRAPHQL_API_URL);
    }

    public function getWalkingTrip($fromLat, $fromLng, $toLat, $toLng) {
        // Construire la requête GraphQL
        $query = (new QueryBuilder('trip'))
            ->setVariable('from', 'Location!', ['lat' => (float)$fromLat, 'lon' => (float)$fromLng])
            ->setVariable('to', 'Location!', ['lat' => (float)$toLat, 'lon' => (float)$toLng])
            ->setVariable('modes', 'Modes', 'WALK')
            ->setSelectionSet([
                'tripPatterns' => [
                    'aimedStartTime',
                    'aimedEndTime',
                    'duration',
                    'distance',
                    'legs' => [
                        'mode',
                        'distance',
                        'duration',
                        'fromPlace' => ['name'],
                        'toPlace' => ['name'],
                        'line' => ['publicCode', 'name']
                    ]
                ]
            ]);

        try {
            // Exécuter la requête
            $response = $this->client->runQuery($query->getQueryString(), true, $query->getVariables());
            $data = $response->getData();
            return $data['trip']['tripPatterns'] ?? null;
        } catch (QueryError $exception) {
            // Gérer les erreurs
            error_log($exception->getErrorDetails());
            return null;
        }
    }
}
