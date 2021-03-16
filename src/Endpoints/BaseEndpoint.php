<?php
namespace Budgetlens\BolRetailerApi\Endpoints;

use Budgetlens\BolRetailerApi\Exceptions\BolRetailerException;
use Budgetlens\BolRetailerApi\Client;

abstract class BaseEndpoint
{
    /** @var \Budgetlens\BolRetailerApi\Client */
    protected $apiClient;

    public function __construct(Client $client)
    {
        $this->apiClient = $client;

        $this->boot();
    }

    protected function boot(): void
    {
    }

    protected function buildQueryString(array $filters): string
    {
        if (empty($filters)) {
            return '';
        }

        return '?'.http_build_query($filters);
    }

    /**
     * Performs a HTTP call to the API endpoint.
     *
     * @param  string  $httpMethod
     * @param  string  $apiMethod
     * @param  string|null  $httpBody
     * @param  array  $requestHeaders
     * @return string|object|null
     *
     * @throws \Bol\RetailerApi\Exceptions\BolRetailerException
     */
    protected function performApiCall(
        string $httpMethod,
        string $apiMethod,
        ?string $httpBody = null,
        array $requestHeaders = []
    ) {
        $response = $this->apiClient->performHttpCall($httpMethod, $apiMethod, $httpBody, $requestHeaders);

        if (collect($response->getHeader('Content-Type'))->first() == 'application/pdf') {
            return $response->getBody()->getContents();
        }

        $body = $response->getBody()->getContents();

        if (empty($body)) {
            if ($response->getStatusCode() === Client::HTTP_STATUS_NO_CONTENT) {
                return;
            }

            throw new BolRetailerException('No response body found.');
        }

        $object = @json_decode($body);

        if (json_last_error() != JSON_ERROR_NONE) {
            throw new BolRetailerException("Unable to decode response: '{$body}'.");
        }

        // error handling
        if ($response->getStatusCode() >= 400) {
            $error = collect($object);
            $messageBag = collect('Error executing API call');

            if ($error->has('detail')) {
                $messageBag->push(': ' . $error->get('detail'));
            }

            if ($error->has('title')) {
                $messageBag->push(': '. $error->get('title'));
            }
            if ($error->has('status')) {
                $messageBag->push('(' . $error->get('status') . ')');
            }

            throw new BolRetailerException($messageBag->implode(' '), $response->getStatusCode());
        }

        return $object;
    }
}
