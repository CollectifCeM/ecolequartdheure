<?php
require_once __DIR__ . '/../vendor/autoload.php';

use GraphQL\Language\Parser;
use GraphQL\Language\AST\DocumentNode;
use GuzzleHttp\Client as HttpClient;

class OTPModel {
    private $apiUrl;
    private $httpClient;

    public function __construct() {
        $this->apiUrl = GRAPHQL_API_URL;
        $this->httpClient = new HttpClient(); // Guzzle Client
    }

    public function getWalkingTrip($fromLat, $fromLng, $toLat, $toLng) {
        // Construire la requête GraphQL
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

        // Valider la requête avec Webonyx
        try {
            $parsedQuery = Parser::parse($query); // Valide la syntaxe GraphQL
        } catch (\Exception $e) {
            error_log('Invalid GraphQL query: ' . $e->getMessage());
            return null;
        }

        // Variables pour la requête
        $variables = [
            'from' => ['lat' => (float)$fromLat, 'lon' => (float)$fromLng],
            'to' => ['lat' => (float)$toLat, 'lon' => (float)$toLng],
            'modes' => 'WALK',
        ];

        // Exécuter la requête
        return $this->executeGraphQLQuery($parsedQuery, $variables);
    }

    private function executeGraphQLQuery(DocumentNode $query, $variables) {
        // Convertir le DocumentNode en texte GraphQL
        $queryString = (string)$query;

        // Préparer la charge utile pour l'API GraphQL
        $payload = [
            'query' => $queryString,
            'variables' => $variables,
        ];

        try {
            // Envoyer la requête avec Guzzle
            $response = $this->httpClient->post($this->apiUrl, [
                'headers' => ['Content-Type' => 'application/json'],
                'body' => json_encode($payload),
            ]);

            // Décoder la réponse
            $responseBody = json_decode($response->getBody(), true);

            if (isset($responseBody['errors'])) {
                error_log('GraphQL Errors: ' . json_encode($responseBody['errors']));
                return null;
            }

            return $responseBody['data'] ?? null;
        } catch (\Exception $e) {
            error_log('GraphQL Request Error: ' . $e->getMessage());
            return null;
        }
    }
}
