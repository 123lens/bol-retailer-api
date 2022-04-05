<?php
namespace Budgetlens\BolRetailerApi;

use GuzzleHttp\ClientInterface;

interface ApiClient
{
    public function initializeEndpoints(): void;

    public function setClient(ClientInterface $client);

    public function getClient(): ClientInterface;

    public function performHttpCall(
        string  $httpMethod,
        string  $apiMethod,
        ?string $httpBody = null,
        array   $requestHeaders = []
    );
}
