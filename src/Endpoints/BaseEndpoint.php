<?php
namespace Budgetlens\BolRetailerApi\Endpoints;

use Budgetlens\BolRetailerApi\Exceptions\BolRetailerException;
use Budgetlens\BolRetailerApi\Client;
use Budgetlens\BolRetailerApi\Exceptions\RateLimitException;
use Budgetlens\BolRetailerApi\Exceptions\ValidationException;

abstract class BaseEndpoint
{
    /** @var \Budgetlens\BolRetailerApi\Client */
    protected $apiClient;

    public function __construct(Client $client)
    {
        $this->apiClient = $client;

        $this->boot();
    }

    /**
     * Overwrite default api version header
     * @param string $versionHeader
     * @return string
     */
    protected function setApiVersionHeader(string $versionHeader): void
    {
        $this->apiClient->apiVersionHeader = $versionHeader;
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

        $directResponseHeaders = [
            'application/vnd.retailer.v4+pdf;charset=UTF-8',
            'application/vnd.retailer.v4+csv;charset=UTF-8',
            'application/vnd.retailer.v4+xml;charset=UTF-8'
        ];

        if (in_array(collect($response->getHeader('Content-Type'))->first(), $directResponseHeaders)) {
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

        // hit a rate limit ?
        if ($response->getStatusCode() === 429) {
            $retryAfter = collect($response->getHeader('Retry-After'))->first();
            throw new RateLimitException($retryAfter);
        }

        // error handling
        if ($response->getStatusCode() >= 400) {
            $error = collect($object);
            // do we have violations?
            if ($error->has('violations')) {
                throw new ValidationException($error->get('violations'));
            }

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
