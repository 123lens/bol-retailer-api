<?php
namespace Budgetlens\BolRetailerApi\Tests;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidFileException;
use Dotenv\Exception\InvalidPathException;
use Budgetlens\BolRetailerApi\Client;
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

    protected function setUp(): void
    {
        try {
            (Dotenv::createUnsafeImmutable(__DIR__.'/..'))->load();
        } catch (InvalidPathException $e) {
            //
        } catch (InvalidFileException $e) {
            exit('The environment file is invalid: '.$e->getMessage());
        }

        $this->client = new Client();

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


    protected function useMock($file)
    {

        // set mock client
        $mockHandler = new MockHandler();
        $client = new \GuzzleHttp\Client([
            'handler' => $mockHandler
        ]);
        $mockHandler->append(new Response(
            200,
            $this->defaultResponseHeader,
            $this->getMockfile($file)
        ));
        $this->client->setClient($client);
    }
}
