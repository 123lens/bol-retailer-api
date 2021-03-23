<?php
namespace Budgetlens\BolRetailerApi\Tests\Feature\Endpoints;

use Budgetlens\BolRetailerApi\Client;
use Budgetlens\BolRetailerApi\Exceptions\BolRetailerException;
use Budgetlens\BolRetailerApi\Exceptions\ValidationException;
use Budgetlens\BolRetailerApi\Resources\Address;
use Budgetlens\BolRetailerApi\Resources\Fulfilment;
use Budgetlens\BolRetailerApi\Resources\Invoice;
use Budgetlens\BolRetailerApi\Resources\InvoiceItem;
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

class InvoicesTest extends TestCase
{
    /** @test */
    public function getAllInvoices()
    {
//        $this->useMock('200-get-all-invoices.json');

        $client = new Client();

        $invoices = $client->invoices->list();
        print_r($invoices);
        exit;
        $this->assertInstanceOf(Invoice::class, $invoices);
        $this->assertInstanceOf(Collection::class, $invoices->invoiceListItems);
        $this->assertCount(1, $invoices->invoiceListItems);
        $this->assertInstanceOf(InvoiceItem::class, $invoices->invoiceListItems->first());
        $this->assertSame('4500022543921', $invoices->invoiceListItems->first()->invoiceId);
        $this->assertIsArray($invoices->invoiceListItems->first()->invoiceMediaTypes);
        $this->assertIsArray($invoices->invoiceListItems->first()->specificationMediaTypes);
    }

    /** @test */
    public function getInvoiceJson()
    {
//        $this->useMock('200-orders.json');

        $invoice = $this->client->invoices->get('4500022543921', 'json');
    }
    /** @test */
    public function getInvoicePdf()
    {
//        $this->useMock('200-orders.json');
        $id = '4500022543921';
        $id = '3904228357264';
        $client = new Client();
        $invoice = $client->invoices->get($id, 'pdf');
        $saved = $invoice->save('.');
        echo "Saved?? " . $saved;
        exit;
        echo "Type " . $invoice->type;
        exit;
    }
    /** @test */
    public function getInvoiceXml()
    {
//        $this->useMock('200-orders.json');
        $id = '4500022543921';
        $id = '3904228357264';

        $client = new Client();
        $invoice = $client->invoices->get($id, 'xml');
        echo $invoice->getXml();
        exit;
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
