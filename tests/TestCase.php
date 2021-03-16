<?php
namespace Budgetlens\BolRetailerApi\Tests;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidFileException;
use Dotenv\Exception\InvalidPathException;
use Budgetlens\BolRetailerApi\Client;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
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

        $this->client = new Client(getenv('CLIENT_ID'), getenv('CLIENT_SECRET'));

        parent::setUp();
    }
}
