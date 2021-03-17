<?php
namespace Budgetlens\BolRetailerApi\Tests\Feature\Endpoints;

use Budgetlens\BolRetailerApi\Exceptions\BolRetailerException;
use Budgetlens\BolRetailerApi\Resources\Address;
use Budgetlens\BolRetailerApi\Resources\Fulfilment;
use Budgetlens\BolRetailerApi\Resources\Offer;
use Budgetlens\BolRetailerApi\Resources\Order;
use Budgetlens\BolRetailerApi\Resources\Product;
use Budgetlens\BolRetailerApi\Tests\TestCase;

class OrdersTest extends TestCase
{
    /** @test */
    public function getOpenFbrOrders()
    {
        $this->useMock('200-orders.json');

        $orders = $this->client->orders->getOpenOrders();
        $this->assertInstanceOf(Order::class, $orders->first());
        $this->assertNotNull($orders->first()->orderId);
        $this->assertNotNull($orders->first()->orderPlacedDateTime);
        $this->assertInstanceOf(\DateTime::class, $orders->first()->orderPlacedDateTime);
        $this->assertTrue(count($orders->first()->orderItems) > 0);
    }

    /** @test */
    public function getOpenFbbOrders()
    {
        $this->useMock('200-orders.json');

        $orders = $this->client->orders->getOpenOrders('fbb');
        $this->assertInstanceOf(Order::class, $orders->first());
        $this->assertNotNull($orders->first()->orderId);
        $this->assertNotNull($orders->first()->orderPlacedDateTime);
        $this->assertInstanceOf(\DateTime::class, $orders->first()->orderPlacedDateTime);
        $this->assertTrue(count($orders->first()->orderItems) > 0);
    }

    /** @test */
    public function getOpenFbrOrdersWithPaging()
    {
        $this->useMock('200-orders.json');

        $orders = $this->client->orders->getOpenOrders('FBR', 3);
        $this->assertInstanceOf(Order::class, $orders->first());
        $this->assertNotNull($orders->first()->orderId);
        $this->assertNotNull($orders->first()->orderPlacedDateTime);
        $this->assertInstanceOf(\DateTime::class, $orders->first()->orderPlacedDateTime);
        $this->assertTrue(count($orders->first()->orderItems) > 0);
    }

    /** @test */
    public function getOrderById()
    {
        $this->useMock('200-order-details.json');
        $order = $this->client->orders->get('1234');
        $this->assertInstanceOf(Order::class, $order);
        $this->assertNotNull($order->orderId);
        $this->assertInstanceOf(Address::class, $order->billingDetails);
        $this->assertInstanceOf(Address::class, $order->shipmentDetails);
        $this->assertCount(1, $order->orderItems);
        $this->assertNotNull($order->orderItems->first()->orderItemId);
        $this->assertInstanceOf(Fulfilment::class, $order->orderItems->first()->fulfilment);
        $this->assertInstanceOf(Offer::class, $order->orderItems->first()->offer);
        $this->assertInstanceOf(Product::class, $order->orderItems->first()->product);
        $this->assertSame('FBR', $order->orderItems->first()->fulfilment->method);
        $this->assertSame('8c5d5aa9-5f01-7849-e053-828b620a2bd4', $order->orderItems->first()->offer->offerId);
        $this->assertSame('8717185945140', $order->orderItems->first()->product->ean);
        $this->assertSame(1, $order->orderItems->first()->quantity);
        $this->assertSame(2825, $order->orderItems->first()->unitPrice);
        $this->assertSame(45, $order->orderItems->first()->commission);
    }

    /** @test */
    public function unknownOrderThrowsException()
    {
        $this->useMock('404-order-not-found.json', 404);
        $this->expectException(BolRetailerException::class);
        $this->expectExceptionMessage('Error executing API call : Order for order id 9999999999 not found. : Not Found (404)');

        $this->client->orders->get('9999999999');
    }
}
