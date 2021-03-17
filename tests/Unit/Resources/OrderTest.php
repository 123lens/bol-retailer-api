<?php
namespace Budgetlens\BolRetailerApi\Tests\Unit\Resources;

use Budgetlens\BolRetailerApi\Resources\Order;
use Budgetlens\BolRetailerApi\Tests\TestCase;

class OrderTest extends TestCase
{
    /** @test */
    public function initiateOrderResource()
    {
        $order = new Order([
            'orderId' => 1,
            'orderPlacedDateTime' => '2019-04-29T16:18:21+02:00',
            'orderItems' => [
                [
                    'orderItemId' => "6042823871",
                    'ean' => "8785075035214",
                    'quantity' => 3
                ]
            ]
        ]);

        $this->assertSame(1, $order->orderId);
        $this->assertSame('2019-04-29', $order->orderPlacedDateTime->format('Y-m-d'));
        $this->assertInstanceOf(\DateTime::class, $order->orderPlacedDateTime);
        $this->assertCount(1, $order->orderItems);
    }

    /** @test */
    public function priceFormatInputReplacedWithInt()
    {
        $order = new Order([
            'orderId' => 1,
            'orderPlacedDateTime' => '2019-04-29T16:18:21+02:00',
            'orderItems' => [
                [
                    'orderItemId' => "6042823871",
                    'ean' => "8785075035214",
                    'quantity' => 3,
                    'unitPrice' => 28.25
                ]
            ]
        ]);
        $this->assertSame(2825, $order->orderItems->first()->unitPrice);
    }

    /** @test */
    public function toArray()
    {
        $order = new Order([
            'orderId' => 1,
            'orderPlacedDateTime' => '2019-04-29T16:18:21+02:00',
            'orderItems' => [
                [
                    'orderItemId' => "6042823871",
                    'ean' => "8785075035214",
                    'quantity' => 3,
                    'unitPrice' => 28.25
                ]
            ]
        ]);

        $array = $order->toArray();

        $this->assertIsArray($array);
        $this->assertSame(1, $array['orderId']);
    }
}
