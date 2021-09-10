<?php
namespace Budgetlens\BolRetailerApi\Tests\Feature\Endpoints;

use Budgetlens\BolRetailerApi\ApiConfig;
use Budgetlens\BolRetailerApi\Client;
use Budgetlens\BolRetailerApi\Endpoints\BaseEndpoint;
use Budgetlens\BolRetailerApi\Exceptions\AuthenticationException;
use Budgetlens\BolRetailerApi\Exceptions\BolRetailerException;
use Budgetlens\BolRetailerApi\Tests\TestCase;

class BaseEndpointTest extends TestCase
{
    /** @test */
    public function invalidCredentialsThrowsAnException()
    {
        // overwrite client
        $client = new Client(new ApiConfigStub());
        $this->expectException(AuthenticationException::class);
        $endpoint = new BaseEndpointStub($client);
        $endpoint->performApiCall('GET', 'non-existent');
    }

    /** @test */
    public function passingAnEmptyArrayToBuildQueryStringShouldReturnAnEmptyString()
    {
        $endpoint = new BaseEndpointStub($this->client);

        $this->assertEquals('', $endpoint->buildQueryString([]));
    }
}

class BaseEndpointStub extends BaseEndpoint
{
    public function buildQueryString(array $filters): string
    {
        return parent::buildQueryString($filters);
    }

    public function performApiCall($httpMethod, $apiMethod, $httpBody = null, $requestHeaders = [])
    {
        return parent::performApiCall($httpMethod, $apiMethod, $httpBody = null, $requestHeaders = []);
    }
}

class ApiConfigStub extends ApiConfig
{

    public function getClientId(): string
    {
        return 'invalid';
    }

    public function getClientSecret(): string
    {
        return 'test';
    }

    public function cacheToken(): bool
    {
        return false;
    }
}
