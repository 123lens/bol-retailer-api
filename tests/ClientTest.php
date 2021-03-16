<?php
namespace Budgetlens\BolRetailerApi\Tests;

use Budgetlens\BolRetailerApi\Endpoints\Orders;
use Budgetlens\BolRetailerApi\Exceptions\BolRetailerException;

class ClientTest extends TestCase
{
    /** @test */
    public function client_has_a_shipments_endpoint()
    {
        $this->assertInstanceOf(Orders::class, $this->client->shipments);
    }

    /** @test */
    public function performing_an_http_call_without_setting_an_api_key_throws_an_exception()
    {
        $this->expectException(BolRetailerException::class);
        $this->client->setApiKey(null);
        $this->client->performHttpCall('GET', 'non-existing-resource');
    }
}
