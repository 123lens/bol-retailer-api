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
use Budgetlens\BolRetailerApi\Endpoints\Shipments;
use Budgetlens\BolRetailerApi\Exceptions\BolRetailerException;
use Psr\Http\Message\ResponseInterface;

class Client
{
    const API_ENDPOINT = 'https://api.bol.com/retailer';
    protected const API_VERSION_CONTENT_TYPE = 'application/vnd.retailer.v4+json';

    const HTTP_STATUS_NO_CONTENT = 204;

    /** @var Config  */
    private $config;

    /** @var string */
    protected $apiEndpoint = self::API_ENDPOINT;

    /** @var string */
    private $clientId;

    /** @var string */
    private $clientSecret;

    /** @var array|null */
    private $token = null;

    /** @var \Budgetlens\BolRetailerApi\Endpoints\Shipments */
    public $shipments;

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
        $this->shipments = new Shipments($this);
    }

    /**
     * Check if the client is authenticated.
     *
     * @return bool
     */
    public function isAuthenticated(): bool
    {
        if (!is_array($this->token)) {
            return false;
        }

        if (!isset($this->token['expires_at']) ||
            !isset($this->token['access_token'])
        ) {
            return false;
        }

        return $this->token['expires_at'] > time();
    }

    /**
     * Authenticate
     *
     * @throws AuthenticationException
     */
    public function authenticate(): void
    {
        $headers = [
            'Accept' => 'application/json'
        ];
        try {
            $response = $this->httpClient->request('POST', 'https://login.bol.com/token?grant_type=client_credentials', [
                'headers' => $headers,
                'auth' => [$this->clientId, $this->clientSecret]
            ]);
        } catch (GuzzleException $e) {
            if ($e instanceof RequestException) {
                $response = json_decode((string)$e->getResponse()->getBody(), true);
                throw new AuthenticationException($response['error_description'] ?? null);
            }

            throw new AuthenticationException($e->getMessage());
        }

        $token = json_decode((string)$response->getBody()->getContents(), true);
        if (!is_array($token) ||
            empty($token['access_token']) ||
            empty($token['expires_in'])
        ) {
            throw new AuthenticationException('Could not retrieve valid token from Bol API');
        }

        $token['expires_at'] = time() + $token['expires_in'] ?? 0;

        $this->token = $token;
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
//        if (!$this->isAuthenticated()) {
//            // authenticate
//            $this->authenticate();
//        }
//
        $headers = collect([
            'Accept' => self::API_VERSION_CONTENT_TYPE,
//            'Authorization' => 'Bearer ' . $this->token['access_token']
        ])
            ->when($httpBody !== null, function ($collection) {
                return $collection->put('Content-Type', 'application/json');
            })
            ->merge($requestHeaders)
            ->all();

        $request = new Request(
            $httpMethod,
            $this->apiEndpoint.'/'.$apiMethod,
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
