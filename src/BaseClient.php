<?php
namespace Budgetlens\BolRetailerApi;

use Budgetlens\BolRetailerApi\Contracts\Config;
use Budgetlens\BolRetailerApi\Middleware\RefreshToken;
use Composer\CaBundle\CaBundle;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Budgetlens\BolRetailerApi\Exceptions\BolRetailerException;
use Psr\Http\Message\ResponseInterface;

abstract class BaseClient
{
    protected $endpoint = 'https://api.bol.com/retailer';
    protected $endpointTest = 'https://api.bol.com/retailer-demo';

    /** @var int http status code - no content */
    const HTTP_STATUS_NO_CONTENT = 204;

    /** @var string - default user agent string */
    const USER_AGENT = "Budgetlens/BolRetailerApi/V9.0.0";

    /** @var string */
    public $apiVersionHeader = 'application/vnd.retailer.v9+json';

    /** @var Config  */
    private $config;

    /** @var \GuzzleHttp\Client */
    protected $httpClient;

    public function __construct(?Config $config = null)
    {
        if (is_null($config)) {
            $config = new ApiConfig();
        }

        // set config
        $this->config = $config;

        // initialize available endpoints
        $this->initializeEndpoints();
    }


    /**
     * Initialize available endpoints
     */
    public function initializeEndpoints(): void
    {
    }

    /**
     * Set Client
     * @param ClientInterface $client
     */
    public function setClient(ClientInterface $client)
    {
        $this->httpClient = $client;
    }

    /**
     * Get Client
     * @return ClientInterface
     */
    public function getClient(): ClientInterface
    {
        if (is_null($this->httpClient)) {
            $stack = HandlerStack::create();

            // add token middleware
            $stack->push(new RefreshToken($this->config));

            foreach ($this->config->getMiddleware() as $middlware) {
                $stack->push($middlware);
            }

            $client = new HttpClient([
                RequestOptions::VERIFY => CaBundle::getBundledCaBundlePath(),
                'handler' => $stack,
                'timeout' => $this->config->getTimeout(),
            ]);

            $this->setClient($client);
        }

        return $this->httpClient;
    }

    /**
     * Retrieve User Agent
     * @return string
     */
    protected function getUserAgent(): string
    {
        $agent = $this->config->getUserAgent();

        return $agent !== '' ? $agent : self::USER_AGENT;
    }

    /**
     * @throws \Budgetlens\BolRetailerApi\Exceptions\BolRetailerException
     */
    public function performHttpCall(
        string $httpMethod,
        string $apiMethod,
        ?string $httpBody = null,
        array $requestHeaders = []
    ): ResponseInterface {
        $headers = collect([
            'Accept' => $this->apiVersionHeader,
            'Content-Type' => $this->apiVersionHeader,
            'User-Agent' => $this->getUserAgent()
        ])
            ->merge($requestHeaders)
            ->all();

        $request = new Request(
            $httpMethod,
            "{$this->getEndpoint()}/{$apiMethod}",
            $headers,
            $httpBody
        );

        try {
            $response = $this->getClient()->send($request, ['http_errors' => false, 'debug' => false]);

            if (!$response) {
                throw new BolRetailerException('No API response received.');
            }

            return $response;
        } catch (GuzzleException $e) {
            throw new BolRetailerException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Get Correct endpoint
     * @return string
     */
    protected function getEndpoint(): string
    {
        return $this->config->getTestMode()
            ? $this->endpointTest
            : $this->endpoint;
    }
}
