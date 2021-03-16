<?php
namespace Budgetlens\BolRetailerApi;

use Budgetlens\BolRetailerApi\Contracts\Config;
use Budgetlens\BolRetailerApi\Exceptions\AuthenticationException;
use Budgetlens\BolRetailerApi\Middleware\RefreshToken;
use Composer\CaBundle\CaBundle;
use GuzzleHttp\Client as HttpClient;
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

        $stack = HandlerStack::create();

        foreach ($config->getMiddleware() as $middlware) {
            $stack->push($middlware);
        }
        $this->httpClient = new HttpClient([
            RequestOptions::VERIFY => CaBundle::getBundledCaBundlePath(),
            'handler' => $stack,
        ]);
        // add token middleware
        $stack->push(new RefreshToken($config));

        $this->initializeEndpoints();
    }

    /**
     * Initialize available endpoints
     */
    public function initializeEndpoints(): void
    {
        $this->shipments = new Orders($this);
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
            $response = $this->httpClient->send($request, ['http_errors' => false, 'debug' => false]);

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
