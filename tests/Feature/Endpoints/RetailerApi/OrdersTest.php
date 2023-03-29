<?php

namespace Budgetlens\BolRetailerApi\Tests\Feature\Endpoints\RetailerApi;

use Budgetlens\BolRetailerApi\Exceptions\BolRetailerException;
use Budgetlens\BolRetailerApi\Exceptions\ValidationException;
use Budgetlens\BolRetailerApi\Resources\Address;
use Budgetlens\BolRetailerApi\Resources\Fulfilment;
use Budgetlens\BolRetailerApi\Resources\Offer;
use Budgetlens\BolRetailerApi\Resources\Order;
use Budgetlens\BolRetailerApi\Resources\OrderItem;
use Budgetlens\BolRetailerApi\Resources\Product;
use Budgetlens\BolRetailerApi\Resources\Transport;
use Budgetlens\BolRetailerApi\Tests\TestCase;
use Budgetlens\BolRetailerApi\Resources\ProcessStatus;
use Budgetlens\BolRetailerApi\Types\CancelReasonCodes;
use Budgetlens\BolRetailerApi\Types\EventTypes;
use Illuminate\Support\Collection;

class OrdersTest extends TestCase
{
    /** @test */
    public function getAllOrders()
    {
        $this->useMock('200-get-all-orders.json');

        $orders = $this->client->orders->getOrders();

        $this->assertInstanceOf(Collection::class, $orders);
        $this->assertInstanceOf(Order::class, $orders->first());
        $this->assertNotNull($orders->first()->orderId);
        $this->assertNotNull($orders->first()->orderPlacedDateTime);
        $this->assertInstanceOf(\DateTime::class, $orders->first()->orderPlacedDateTime);
        $this->assertTrue(count($orders->first()->orderItems) > 0);
        $this->assertSame('1043946570', $orders->first()->orderId);
        $this->assertSame('217a5e27aba44dc943b02e17782fcbb0', $orders->first()->orderHash);
        $this->assertInstanceOf(Collection::class, $orders->first()->orderItems);

        // get first order item as shorthand
        $orderItem = $orders->first()->orderItems->first();

        $this->assertInstanceOf(Order\OrderItem::class, $orderItem);
        $this->assertInstanceOf(Fulfilment::class, $orderItem->fulfilment);
        $this->assertSame('6042823871', $orderItem->orderItemId);
        $this->assertSame(false, $orderItem->cancellationRequest);
        $this->assertSame('FBR', $orderItem->fulfilment->method);
        $this->assertSame('OPEN', $orderItem->fulfilment->status);
        $this->assertInstanceOf(\DateTime::class, $orderItem->latestChangedDateTime);
        $this->assertSame('8717418510749', $orderItem->ean);
        $this->assertSame(3, $orderItem->quantity);
        $this->assertSame(3, $orderItem->quantityShipped);
        $this->assertSame(0, $orderItem->quantityCancelled);
    }

    /** @test */
    public function getOpenFbrOrders()
    {
        $this->useMock('200-orders-fbr.json');

        $orders = $this->client->orders->getOpenOrders();

        $this->assertInstanceOf(Order::class, $orders->first());
        $this->assertNotNull($orders->first()->orderId);
        $this->assertNotNull($orders->first()->orderPlacedDateTime);
        $this->assertInstanceOf(\DateTime::class, $orders->first()->orderPlacedDateTime);
        $this->assertTrue(count($orders->first()->orderItems) > 0);

        // get first order item as shorthand
        $orderItem = $orders->first()->orderItems->first();

        $this->assertInstanceOf(Order\OrderItem::class, $orderItem);
        $this->assertInstanceOf(Fulfilment::class, $orderItem->fulfilment);
        $this->assertSame('6042823871', $orderItem->orderItemId);
        $this->assertSame(false, $orderItem->cancellationRequest);
        $this->assertSame('FBR', $orderItem->fulfilment->method);
        $this->assertSame('OPEN', $orderItem->fulfilment->status);
        $this->assertInstanceOf(\DateTime::class, $orderItem->latestChangedDateTime);
        $this->assertSame('8717418510749', $orderItem->ean);
        $this->assertSame(3, $orderItem->quantity);
        $this->assertSame(3, $orderItem->quantityShipped);
        $this->assertSame(0, $orderItem->quantityCancelled);
    }

    /** @test */
    public function getOpenFbbOrders()
    {
        $this->useMock('200-orders-fbb.json');

        $orders = $this->client->orders->getOpenOrders('fbb');
        $this->assertInstanceOf(Order::class, $orders->first());
        $this->assertNotNull($orders->first()->orderId);
        $this->assertNotNull($orders->first()->orderPlacedDateTime);
        $this->assertInstanceOf(\DateTime::class, $orders->first()->orderPlacedDateTime);
        $this->assertTrue(count($orders->first()->orderItems) > 0);

        // get first order item as shorthand
        $orderItem = $orders->first()->orderItems->first();

        $this->assertInstanceOf(Order\OrderItem::class, $orderItem);
        $this->assertInstanceOf(Fulfilment::class, $orderItem->fulfilment);
        $this->assertSame('6107989317', $orderItem->orderItemId);
        $this->assertSame(false, $orderItem->cancellationRequest);
        $this->assertSame('FBB', $orderItem->fulfilment->method);
        $this->assertSame('OPEN', $orderItem->fulfilment->status);
        $this->assertInstanceOf(\DateTime::class, $orderItem->latestChangedDateTime);
        $this->assertSame('8717418510749', $orderItem->ean);
        $this->assertSame(2, $orderItem->quantity);
        $this->assertSame(1, $orderItem->quantityShipped);
        $this->assertSame(1, $orderItem->quantityCancelled);
    }

    /** @test */
    public function getOpenFbrOrdersWithPaging()
    {
        $this->useMock('200-orders-fbr.json');

        $orders = $this->client->orders->getOpenOrders('FBR', 3);
        $this->assertInstanceOf(Order::class, $orders->first());
        $this->assertNotNull($orders->first()->orderId);
        $this->assertNotNull($orders->first()->orderPlacedDateTime);
        $this->assertInstanceOf(\DateTime::class, $orders->first()->orderPlacedDateTime);
        $this->assertTrue(count($orders->first()->orderItems) > 0);
    }

    /** @test */
    public function getOpenFbbOrdersWithPaging()
    {
        $this->useMock('200-orders-fbb.json');

        $orders = $this->client->orders->getOpenOrders('FBB', 3);
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
        $orderId = '1042823870';
        $order = $this->client->orders->get($orderId);
        $this->assertInstanceOf(Order::class, $order);
        $this->assertNotNull($order->orderId);
        $this->assertSame('1042823870', $order->orderId);
        $this->assertSame('cb4058f1a52a36dce2180a7a9fca021a', $order->orderHash);
        $this->assertInstanceOf(Address::class, $order->shipmentDetails);
        $this->assertSame('MALE', $order->shipmentDetails->salutation);
        $this->assertSame('Hans', $order->shipmentDetails->firstName);
        $this->assertSame('de Grote', $order->shipmentDetails->surname);
        $this->assertSame('Skywalkerstraat', $order->shipmentDetails->streetName);
        $this->assertSame('199', $order->shipmentDetails->houseNumber);
        $this->assertSame('1234AB', $order->shipmentDetails->zipCode);
        $this->assertSame('PLATOONDORP', $order->shipmentDetails->city);
        $this->assertSame('NL', $order->shipmentDetails->countryCode);
        $this->assertSame('27zoytzc3crf2r6bctxfa3m2mrjbci@verkopen.test2.bol.com', $order->shipmentDetails->email);
        $this->assertInstanceOf(Address::class, $order->billingDetails);
        $this->assertSame('MALE', $order->billingDetails->salutation);
        $this->assertSame('Pieter', $order->billingDetails->firstName);
        $this->assertSame('Post', $order->billingDetails->surname);
        $this->assertSame('Skywalkerstraat', $order->billingDetails->streetName);
        $this->assertSame('21', $order->billingDetails->houseNumber);
        $this->assertSame('X', $order->billingDetails->houseNumberExtension);
        $this->assertSame('Extra informatie', $order->billingDetails->extraAddressInformation);
        $this->assertSame('1234AB', $order->billingDetails->zipCode);
        $this->assertSame('PLATOONDORP', $order->billingDetails->city);
        $this->assertSame('NL', $order->billingDetails->countryCode);
        $this->assertSame('2yldzdi2wjcf5ir4sycq7lufqpytxy@verkopen.test2.bol.com', $order->billingDetails->email);
        $this->assertSame('Pieter Post', $order->billingDetails->company);
        $this->assertSame('NL123456789B01', $order->billingDetails->vatNumber);
        $this->assertSame('99887766', $order->billingDetails->kvkNumber);
        $this->assertSame('Mijn order ref', $order->billingDetails->orderReference);
        $this->assertInstanceOf(\DateTime::class, $order->orderPlacedDateTime);
        $this->assertInstanceOf(Collection::class, $order->orderItems);
        $this->assertCount(2, $order->orderItems);
        $this->assertInstanceOf(Order\OrderItem::class, $order->orderItems->first());
        $this->assertInstanceOf(\DateTime::class, $order->orderItems->first()->latestChangedDateTime);
        $this->assertNotNull($order->orderItems->first()->orderItemId);
        $this->assertSame('6107771545', $order->orderItems->first()->orderItemId);
        $this->assertIsBool($order->orderItems->first()->cancellationRequest);
        $this->assertInstanceOf(Fulfilment::class, $order->orderItems->first()->fulfilment);
        $this->assertSame('FBB', $order->orderItems->first()->fulfilment->method);
        $this->assertInstanceOf(\DateTime::class, $order->orderItems->first()->fulfilment->latestDeliveryDate);
        $this->assertInstanceOf(\DateTime::class, $order->orderItems->first()->fulfilment->expiryDate);
        $this->assertSame('REGULAR', $order->orderItems->first()->fulfilment->timeFrameType);
        $this->assertInstanceOf(Offer::class, $order->orderItems->first()->offer);
        $this->assertSame('8f1183e3-de98-c92f-e053-3590612a63b7', $order->orderItems->first()->offer->offerId);
        $this->assertSame('MijnOffer0021', $order->orderItems->first()->offer->reference);
        $this->assertInstanceOf(Product::class, $order->orderItems->first()->product);
        $this->assertSame('8785056370398', $order->orderItems->first()->product->ean);
        $this->assertSame('Star Wars Prequel Trilogy', $order->orderItems->first()->product->title);
        $this->assertSame(1, $order->orderItems->first()->quantity);
        $this->assertSame(1, $order->orderItems->first()->quantityShipped);
        $this->assertSame(0, $order->orderItems->first()->quantityCancelled);
        $this->assertSame(19.99, $order->orderItems->first()->unitPrice);
        $this->assertSame(2.21, $order->orderItems->first()->commission);
    }

    /** @test */
    public function unknownOrderThrowsException()
    {
        $this->useMock('404-order-not-found.json', 404);
        $this->expectException(BolRetailerException::class);
        $this->expectExceptionMessage('Error executing API call : Order for order id 9999999999 not found. : Not Found (404)');

        $this->client->orders->get('9999999999');
    }

    /** @test */
    public function shipOrderItemUsingTransport()
    {
        $this->useMock('200-ship-order-item.json');

        $shipmentReference = 'unit-test';
        $transport = new Transport([
            'transporterCode' => 'TNT',
            'trackAndTrace' => '3SAOLD1234567'
        ]);

        $status = $this->client->orders->shipOrderItem('6107434013', $shipmentReference, null, $transport);

        $this->assertInstanceOf(ProcessStatus::class, $status);
        $this->assertSame(1, $status->id);
        $this->assertSame('6107434013', $status->entityId);
        $this->assertSame('CONFIRM_SHIPMENT', $status->eventType);
        $this->assertSame('PENDING', $status->status);
    }

    /** @test */
    public function shipOrderItemUsingShipmentlabel()
    {
        $this->useMock('200-ship-order-item.json');

        $shipmentReference = 'unit-test';
        $shipmentLabelId = 'd4c50077-0c19-435f-9bee-1b30b9f4ba1a';

        $status = $this->client->orders->shipOrderItem('6107434013', $shipmentReference, $shipmentLabelId);

        $this->assertInstanceOf(ProcessStatus::class, $status);
        $this->assertSame(1, $status->id);
        $this->assertSame('6107434013', $status->entityId);
        $this->assertSame('CONFIRM_SHIPMENT', $status->eventType);
        $this->assertSame('PENDING', $status->status);
    }

    /** @test */
    public function shipOrderItemMultipleTransportInformationThrowsException()
    {
        $this->useMock('400-ship-order-items-multiple-transport-information.json', 400);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Validation Failed, See violations');

        $shipmentReference = 'unit-test';
        $shipmentLabelId = 'd4c50077-0c19-435f-9bee-1b30b9f4ba1a';
        $transport = new Transport([
            'transporterCode' => 'TNT',
            'trackAndTrace' => '3SAOLD1234567'
        ]);

        try {
            $status = $this->client->orders->shipOrderItem('6107434013', $shipmentReference, $shipmentLabelId, $transport);
        } catch (ValidationException $e) {
            $violations = $e->getViolations();
            $this->assertSame('Conflicting transport method: Either provide Transport or ShippingLabelId. The current request contains both.', $violations->first()->reason);
            throw $e;
        }
    }

    /** @test */
    public function cancelOrderItemRequestedByCustomer()
    {
        $this->useMock('200-cancel-order-item-success.json');

        $status = $this->client->orders->cancelOrderItem('7616222250', CancelReasonCodes::REQUESTED_BY_CUSTOMER);

        $this->assertInstanceOf(ProcessStatus::class, $status);
        $this->assertSame(1, $status->id);
        $this->assertSame('7616222250', $status->entityId);
        $this->assertSame(EventTypes::CANCEL_ORDER, $status->eventType);
        $this->assertSame('PENDING', $status->status);
    }

    /** @test */
    public function cancelOrderItemInvalidCancelReasonThrowsAnException()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Validation Failed, See violations');

        $this->useMock('400-cancel-order-items-invalid-reason-code.json', 400);

        try {
            $status = $this->client->orders->cancelOrderItem('7616222250', 'Unknown');
        } catch (ValidationException $e) {
            $violations = $e->getViolations();
            $this->assertSame('orderItems[0].reasonCode', $violations->first()->name);
            $this->assertSame("Request contains invalid value(s): 'Unknown', allowed values: OUT_OF_STOCK, REQUESTED_BY_CUSTOMER, BAD_CONDITION, HIGHER_SHIPCOST, INCORRECT_PRICE, NOT_AVAIL_IN_TIME, NO_BOL_GUARANTEE, ORDERED_TWICE, RETAIN_ITEM, TECH_ISSUE, UNFINDABLE_ITEM, OTHER.", $violations->first()->reason);
            throw $e;
        }
    }
}
