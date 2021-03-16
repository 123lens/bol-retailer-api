<?php
namespace Budgetlens\BolRetailerApi\Tests\Feature\Endpoints;

use Budgetlens\BolRetailerApi\Exceptions\BolRetailerException;
use Budgetlens\BolRetailerApi\Resources\Shipment;
use Budgetlens\BolRetailerApi\Tests\TestCase;

class ShipmentsTest extends TestCase
{
    /** @testx */
    public function get_a_shipment_by_its_id()
    {
        $id = 1;
        $shipment = $this->client->shipments->get($id);
        $this->assertInstanceOf(Shipment::class, $shipment);
        $this->assertNotNull($shipment->id);
        $this->assertNotNull($shipment->created);
        $this->assertNotNull($shipment->status);
    }

    /** @testx */
    public function getting_a_shipment_with_an_invalid_id_should_throw_an_error()
    {
        $this->expectException(BolRetailerException::class);
        $this->expectExceptionMessage('Shipment with an id of "9999999999" not found.');

        $this->client->shipments->get('9999999999');
    }

    /** @testx */
    public function getShipmentList()
    {
        $shipments = $this->client->shipments->list();

    }
}
