<?php
namespace Budgetlens\BolRetailerApi;

use Budgetlens\BolRetailerApi\Contracts\Config;
use Budgetlens\BolRetailerApi\Exceptions\AuthenticationException;
use Budgetlens\BolRetailerApi\Middleware\RefreshToken;
use Composer\CaBundle\CaBundle;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Budgetlens\BolRetailerApi\Endpoints\Orders;
use Budgetlens\BolRetailerApi\Exceptions\BolRetailerException;
use Psr\Http\Message\ResponseInterface;

class Client
{
    const HTTP_STATUS_NO_CONTENT = 204;

    /** @var Config  */
    private $config;

    /** @var \Budgetlens\BolRetailerApi\Endpoints\Orders */
    public $orders;

    /** @var \GuzzleHttp\Client */
    protected $httpClient;

    public function __construct(?Config $config = null)
    {
        if (is_null($config)) {
            $config = new ApiConfig();
        }
        $this->config = $config;

        $this->initializeEndpoints();
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

            foreach ($this->config->getMiddleware() as $middlware) {
                $stack->push($middlware);
            }
            $client = new HttpClient([
                RequestOptions::VERIFY => CaBundle::getBundledCaBundlePath(),
                'handler' => $stack,
            ]);
            // add token middleware
            $stack->push(new RefreshToken($this->config));
            $this->setClient($client);
        }

        return $this->httpClient;
    }

    /**
     * Initialize available endpoints
     */
    public function initializeEndpoints(): void
    {
        $this->orders = new Orders($this);
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
            'Accept' => $this->config->getApiVersionHeader(),
        ])
            ->when($httpBody !== null, function ($collection) {
                return $collection->put('Content-Type', 'application/json');
            })
            ->merge($requestHeaders)
            ->all();

        $request = new Request(
            $httpMethod,
            "{$this->config->getEndpoint()}/{$apiMethod}",
            $headers,
            $httpBody
        );

        try {
            $response = $this->getClient()->send($request, ['http_errors' => false, 'debug' => false]);

        } catch (GuzzleException $e) {
            throw new BolRetailerException($e->getMessage(), $e->getCode());
        }

        if (! $response) {
            throw new BolRetailerException('No API response received.');
        }

        return $response;
    }

    public function setApiKey(?string $value): self
    {
        $this->apiKey = trim($value);

        return $this;
    }
}
