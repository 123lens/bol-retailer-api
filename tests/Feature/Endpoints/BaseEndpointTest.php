<?php
namespace Budgetlens\BolRetailerApi\Tests\Feature\Endpoints;

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
        $client = new Client('invalid', 'credentials');
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

    /** @test */
    public function invalidEndpointThrowsAnException()
    {
        $this->expectException(BolRetailerException::class);
        $this->expectExceptionMessage('Error executing API call : Unauthorized request : Unauthorized Request (403)');

        $endpoint = new BaseEndpointStub($this->client);

        $endpoint->performApiCall('GET', 'non-existent');
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
