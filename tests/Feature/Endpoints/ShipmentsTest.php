<?php
namespace Budgetlens\BolRetailerApi\Tests\Feature\Endpoints;

use Budgetlens\BolRetailerApi\Resources\Address;
use Budgetlens\BolRetailerApi\Resources\Fulfilment;
use Budgetlens\BolRetailerApi\Resources\Offer;
use Budgetlens\BolRetailerApi\Resources\Order;
use Budgetlens\BolRetailerApi\Resources\Shipment;
use Budgetlens\BolRetailerApi\Resources\Shipment\ShipmentItem;
use Budgetlens\BolRetailerApi\Resources\Transport;
use Budgetlens\BolRetailerApi\Tests\TestCase;
use Illuminate\Support\Collection;

class ShipmentsTest extends TestCase
{
    /** @test */
    public function getShipmentsList()
    {
        $this->useMock('200-shipments-list.json');

        $shipments = $this->client->shipments->list();
        $this->assertInstanceOf(Collection::class, $shipments);
        $this->assertCount(4, $shipments);
        $this->assertSame(914587795, $shipments->first()->shipmentId);
        $this->assertInstanceOf(\DateTime::class, $shipments->first()->shipmentDateTime);
        // shipmentReference ?
        $this->assertInstanceOf(Order::class, $shipments->first()->order);
        $this->assertSame('7616222250', $shipments->first()->order->orderId);
        $this->assertInstanceOf(\DateTime::class, $shipments->first()->order->orderPlacedDateTime);
        $this->assertInstanceOf(Collection::class, $shipments->first()->shipmentItems);
        $this->assertInstanceOf(ShipmentItem::class, $shipments->first()->shipmentItems->first());
        $this->assertSame('6107434013', $shipments->first()->shipmentItems->first()->orderItemId);
        $this->assertSame('8421152081990', $shipments->first()->shipmentItems->first()->ean);
        $this->assertInstanceOf(Transport::class, $shipments->first()->transport);
        $this->assertSame(358612589, $shipments->first()->transport->transportId);
        $this->assertSame('WSR1096399593', $shipments->last()->shipmentReference);
    }

    /** @test */
    public function getShipmentsFBB()
    {
        $this->useMock('200-shipments-list-fbb.json');

        $shipments = $this->client->shipments->list('FBB');

        $this->assertInstanceOf(Collection::class, $shipments);
        $this->assertCount(1, $shipments);
        $this->assertSame(953992381, $shipments->first()->shipmentId);
        $this->assertInstanceOf(\DateTime::class, $shipments->first()->shipmentDateTime);
        $this->assertInstanceOf(Order::class, $shipments->first()->order);
        $this->assertSame('1045972070', $shipments->first()->order->orderId);
        $this->assertInstanceOf(\DateTime::class, $shipments->first()->order->orderPlacedDateTime);
        $this->assertInstanceOf(Collection::class, $shipments->first()->shipmentItems);
        $this->assertInstanceOf(ShipmentItem::class, $shipments->first()->shipmentItems->first());
        $this->assertSame('2070686066', $shipments->first()->shipmentItems->first()->orderItemId);
        $this->assertSame('8421152081990', $shipments->first()->shipmentItems->first()->ean);
        $this->assertInstanceOf(Transport::class, $shipments->first()->transport);
        $this->assertSame(309119453, $shipments->first()->transport->transportId);
    }

    /** @test */
    public function getShipmentsForOrder()
    {
        $this->useMock('200-shipments-for-order-id.json');

        $shipments = $this->client->shipments->list(null, 7616222250);

        $this->assertInstanceOf(Collection::class, $shipments);
        $this->assertCount(1, $shipments);
        $this->assertSame(914587795, $shipments->first()->shipmentId);
        $this->assertInstanceOf(\DateTime::class, $shipments->first()->shipmentDateTime);
        $this->assertInstanceOf(Order::class, $shipments->first()->order);
        $this->assertSame('7616222250', $shipments->first()->order->orderId);
        $this->assertInstanceOf(\DateTime::class, $shipments->first()->order->orderPlacedDateTime);
        $this->assertInstanceOf(Collection::class, $shipments->first()->shipmentItems);
        $this->assertInstanceOf(ShipmentItem::class, $shipments->first()->shipmentItems->first());
        $this->assertSame('6107434013', $shipments->first()->shipmentItems->first()->orderItemId);
        $this->assertSame('8718712021740', $shipments->first()->shipmentItems->first()->ean);
        $this->assertInstanceOf(Transport::class, $shipments->first()->transport);
        $this->assertSame(358612589, $shipments->first()->transport->transportId);
    }

    /** @test */
    public function getShipmentById()
    {
        $this->useMock('200-get-shipment-by-id.json');

        $id = '914587795';
        $shipment = $this->client->shipments->get($id);
        $this->assertInstanceOf(Shipment::class, $shipment);
        $this->assertSame(914587795, $shipment->shipmentId);
        $this->assertInstanceOf(\DateTime::class, $shipment->shipmentDateTime);
        $this->assertSame('Shipment1', $shipment->shipmentReference);
        $this->assertSame(true, $shipment->pickUpPoint);
        $this->assertInstanceOf(Order::class, $shipment->order);
        $this->assertSame('7616222250', $shipment->order->orderId);
        $this->assertInstanceOf(\DateTime::class, $shipment->order->orderPlacedDateTime);
        $this->assertInstanceOf(Address::class, $shipment->shipmentDetails);
        $this->assertInstanceOf(Address::class, $shipment->billingDetails);
        $this->assertInstanceOf(Collection::class, $shipment->shipmentItems);
        $this->assertInstanceOf(ShipmentItem::class, $shipment->shipmentItems->first());
        $this->assertSame('6107434013', $shipment->shipmentItems->first()->orderItemId);
        $this->assertInstanceOf(Fulfilment::class, $shipment->shipmentItems->first()->fulfilment);
        $this->assertSame('FBR', $shipment->shipmentItems->first()->fulfilment->method);
        $this->assertInstanceOf(Offer::class, $shipment->shipmentItems->first()->offer);
        $this->assertSame('8f6085e3-de98-c97f-e053-3542090a63b3', $shipment->shipmentItems->first()->offer->offerId);
        $this->assertInstanceOf(Transport::class, $shipment->transport);
        $this->assertSame(358612589, $shipment->transport->transportId);
    }
}
