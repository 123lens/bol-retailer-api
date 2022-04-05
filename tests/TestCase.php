<?php
namespace Budgetlens\BolRetailerApi\Tests;

use Budgetlens\BolRetailerApi\Contracts\Config;
use Budgetlens\BolRetailerApi\Client;
use Budgetlens\BolRetailerApi\SharedClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected $defaultResponseHeader = [
        'Content-Type' => [
            'application/json; charset=utf-8'
        ]
    ];

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var SharedClient
     */
    protected $sharedClient;

    protected function setUp(): void
    {
        // setup multiple clients
        $config = new TestApiConfig();
        // retailer api client
        $this->client = new Client($config);
        // shared api client
        $this->sharedClient = new SharedClient($config);
        parent::setUp();
    }

    public function getMockfile(string $filename): ?string
    {
        $file = __DIR__ . "/Mocks/{$filename}";
        if (file_exists($file)) {
            return file_get_contents($file);
        }
        throw new \Exception("Mockfile not found '{$filename}'");
    }


    protected function useMock($file, $status = 200, $header = null)
    {

        // set mock client
        $mockHandler = new MockHandler();
        $client = new \GuzzleHttp\Client([
            'handler' => $mockHandler
        ]);
        $mockHandler->append(new Response(
            $status,
            $header ?? $this->defaultResponseHeader,
            $this->getMockfile($file)
        ));
        $this->client->setClient($client);
    }
}

class TestApiConfig implements Config
{
    private $middleware = [];

    public function getClientId(): string
    {
        return getenv('CLIENT_ID');
    }

    public function getClientSecret(): string
    {
        return getenv('CLIENT_SECRET');
    }

    public function getTestMode(): bool
    {
        return true;
    }

    public function getMiddleware(): array
    {
        return $this->middleware;
    }

    public function addMiddleware($middleware)
    {
        $this->middleware[] = $middleware;
    }

    public function cacheToken(): bool
    {
        return true;
    }
    public function getTimeout(): int
    {
        return 0;
    }
    public function getUserAgent(): string
    {
        return '';
    }
}
