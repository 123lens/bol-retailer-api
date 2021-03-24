<?php
namespace Budgetlens\BolRetailerApi;

use Budgetlens\BolRetailerApi\Contracts\Config;
use Budgetlens\BolRetailerApi\Endpoints\Inbounds;
use Budgetlens\BolRetailerApi\Endpoints\Inventory;
use Budgetlens\BolRetailerApi\Endpoints\Invoices;
use Budgetlens\BolRetailerApi\Endpoints\Offers;
use Budgetlens\BolRetailerApi\Endpoints\Shipments;
use Budgetlens\BolRetailerApi\Endpoints\Shipping;
use Budgetlens\BolRetailerApi\Endpoints\Status;
use Budgetlens\BolRetailerApi\Middleware\RefreshToken;
use Composer\CaBundle\CaBundle;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Budgetlens\BolRetailerApi\Endpoints\Orders;
use Budgetlens\BolRetailerApi\Exceptions\BolRetailerException;
use Psr\Http\Message\ResponseInterface;

class Client
{
    const HTTP_STATUS_NO_CONTENT = 204;

    const USER_AGENT = "Budgetlens/BolRetailerApi/V1.0.0";

    /** @var string */
    public $apiVersionHeader = 'application/vnd.retailer.v4+json';

    /** @var Config  */
    protected $config;

    /** @var \Budgetlens\BolRetailerApi\Endpoints\Orders */
    public $orders;

    /** @var \Budgetlens\BolRetailerApi\Endpoints\Offers */
    public $offers;

    /** @var  \Budgetlens\BolRetailerApi\Endpoints\Status */
    public $status;

    /** @var  \Budgetlens\BolRetailerApi\Endpoints\Shipping */
    public $shipping;

    /** @var  \Budgetlens\BolRetailerApi\Endpoints\Shipments */
    public $shipments;

    /** @var  \Budgetlens\BolRetailerApi\Endpoints\Inventory */
    public $inventory;

    /** @var  \Budgetlens\BolRetailerApi\Endpoints\Invoices */
    public $invoices;

    /** @var  \Budgetlens\BolRetailerApi\Endpoints\Inbounds */
    public $inbounds;

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
        $this->orders = new Orders($this);
        $this->offers = new Offers($this);
        $this->status = new Status($this);
        $this->shipping = new Shipping($this);
        $this->shipments = new Shipments($this);
        $this->inventory = new Inventory($this);
        $this->invoices = new Invoices($this);
        $this->inbounds = new Inbounds($this);
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
                'timeout' => $this->config->getTimeout(),
            ]);
            // add token middleware
            $stack->push(new RefreshToken($this->config));
            $this->setClient($client);
        }

        return $this->httpClient;
    }

    /**
     * Retrieve User Agent
     * @return string
     */
    private function getUserAgent(): string
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
}
