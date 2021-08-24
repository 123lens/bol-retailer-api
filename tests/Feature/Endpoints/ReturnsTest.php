<?php
namespace Budgetlens\BolRetailerApi\Tests\Feature\Endpoints;

use Budgetlens\BolRetailerApi\Client;
use Budgetlens\BolRetailerApi\Resources\Address;
use Budgetlens\BolRetailerApi\Resources\Inbound;
use Budgetlens\BolRetailerApi\Resources\InboundPackinglist;
use Budgetlens\BolRetailerApi\Resources\InboundProductLabels;
use Budgetlens\BolRetailerApi\Resources\InboundShippingLabel;
use Budgetlens\BolRetailerApi\Resources\ProcessStatus;
use Budgetlens\BolRetailerApi\Resources\Replenishment;
use Budgetlens\BolRetailerApi\Resources\Returns;
use Budgetlens\BolRetailerApi\Resources\Timeslot;
use Budgetlens\BolRetailerApi\Resources\Transporter;
use Budgetlens\BolRetailerApi\Tests\TestCase;
use Budgetlens\BolRetailerApi\Types\LabelFormat;
use Budgetlens\BolRetailerApi\Types\ReplenishState;
use Budgetlens\BolRetailerApi\Types\ReturnResultTypes;
use Budgetlens\BolRetailerApi\Types\TransportState;
use Cassandra\Date;
use Illuminate\Support\Collection;

class ReturnsTest extends TestCase
{
    /** @test */
    public function getAllUnhandledFbbReturns()
    {
        $this->useMock('200-get-unhandled-fbb-returns.json');

        $returns = $this->client->returns->list('FBB', false);
        $this->assertInstanceOf(Collection::class, $returns);
        $this->assertCount(1, $returns);
        $this->assertInstanceOf(Returns::class, $returns->first());
        $this->assertNotNull($returns->first()->returnId);
        $this->assertSame('6', $returns->first()->returnId);
        $this->assertInstanceOf(\DateTime::class, $returns->first()->registrationDateTime);
        $this->assertSame('FBB', $returns->first()->fulfilmentMethod);
        $this->assertInstanceOf(Collection::class, $returns->first()->returnItems);
        $this->assertInstanceOf(Returns\Item::class, $returns->first()->returnItems->first());
        $this->assertSame('86127131', $returns->first()->returnItems->first()->rmaId);
        $this->assertSame('7616247328', $returns->first()->returnItems->first()->orderId);
        $this->assertSame('8718526069334', $returns->first()->returnItems->first()->ean);
        $this->assertSame(1, $returns->first()->returnItems->first()->expectedQuantity);
        $this->assertInstanceOf(Returns\ReturnReason::class, $returns->first()->returnItems->first()->returnReason);
        $this->assertSame('Verkeerd besteld', $returns->first()->returnItems->first()->returnReason->mainReason);
        $this->assertSame('Ik wilde eigenlijk een groter formaat', $returns->first()->returnItems->first()->returnReason->customerComments);
        $this->assertSame(false, $returns->first()->returnItems->first()->handled);
    }

    /** @test */
    public function getAllUnhandledReturns()
    {
        $this->useMock('200-get-unhandled-returns.json');

        $returns = $this->client->returns->list(null, false);
        $this->assertInstanceOf(Collection::class, $returns);
        $this->assertCount(1, $returns);
        $this->assertInstanceOf(Returns::class, $returns->first());
        $this->assertNotNull($returns->first()->returnId);
        $this->assertSame('15897410', $returns->first()->returnId);
        $this->assertInstanceOf(\DateTime::class, $returns->first()->registrationDateTime);
        $this->assertSame('FBR', $returns->first()->fulfilmentMethod);
        $this->assertInstanceOf(Collection::class, $returns->first()->returnItems);
        $this->assertInstanceOf(Returns\Item::class, $returns->first()->returnItems->first());
        $this->assertSame('60283608', $returns->first()->returnItems->first()->rmaId);
        $this->assertSame('1044796550', $returns->first()->returnItems->first()->orderId);
        $this->assertSame('0634154562956', $returns->first()->returnItems->first()->ean);
        $this->assertSame(1, $returns->first()->returnItems->first()->expectedQuantity);
        $this->assertInstanceOf(Returns\ReturnReason::class, $returns->first()->returnItems->first()->returnReason);
        $this->assertSame('Anders, namelijk:', $returns->first()->returnItems->first()->returnReason->mainReason);
        $this->assertSame('Cadeau vonden ze niet leuk', $returns->first()->returnItems->first()->returnReason->customerComments);
        $this->assertSame(false, $returns->first()->returnItems->first()->handled);
    }

    /** @test */
    public function getAllHandledFBBReturns()
    {
        $this->useMock('200-get-handled-fbb-returns.json');

        $returns = $this->client->returns->list('FBB', true);
        $this->assertInstanceOf(Collection::class, $returns);
        $this->assertCount(1, $returns);
        $this->assertInstanceOf(Returns::class, $returns->first());
        $this->assertNotNull($returns->first()->returnId);
        $this->assertSame('5', $returns->first()->returnId);
        $this->assertInstanceOf(\DateTime::class, $returns->first()->registrationDateTime);
        $this->assertSame('FBB', $returns->first()->fulfilmentMethod);
        $this->assertInstanceOf(Collection::class, $returns->first()->returnItems);
        $this->assertInstanceOf(Returns\Item::class, $returns->first()->returnItems->first());
        $this->assertSame('86127199', $returns->first()->returnItems->first()->rmaId);
        $this->assertSame('7616247328', $returns->first()->returnItems->first()->orderId);
        $this->assertSame('8718526069334', $returns->first()->returnItems->first()->ean);
        $this->assertSame(1, $returns->first()->returnItems->first()->expectedQuantity);
        $this->assertInstanceOf(Returns\ReturnReason::class, $returns->first()->returnItems->first()->returnReason);
        $this->assertSame('Verkeerd besteld', $returns->first()->returnItems->first()->returnReason->mainReason);
        $this->assertSame('Ik wilde eigenlijk een groter formaat', $returns->first()->returnItems->first()->returnReason->customerComments);
        $this->assertSame(true, $returns->first()->returnItems->first()->handled);
        $this->assertInstanceOf(Collection::class, $returns->first()->returnItems->first()->processingResults);
        $this->assertCount(2, $returns->first()->returnItems->first()->processingResults);

        $this->assertSame(1, $returns->first()->returnItems->first()->processingResults->first()->quantity);
        $this->assertSame('ACCEPTED', $returns->first()->returnItems->first()->processingResults->first()->processingResult);
        $this->assertSame('RETURN_RECEIVED', $returns->first()->returnItems->first()->processingResults->first()->handlingResult);
        $this->assertInstanceOf(\DateTime::class, $returns->first()->returnItems->first()->processingResults->first()->processingDateTime);

        $this->assertSame(1, $returns->first()->returnItems->first()->processingResults->last()->quantity);
        $this->assertSame('ACCEPTED', $returns->first()->returnItems->first()->processingResults->last()->processingResult);
        $this->assertSame('RETURN_RECEIVED', $returns->first()->returnItems->first()->processingResults->last()->handlingResult);
        $this->assertInstanceOf(\DateTime::class, $returns->first()->returnItems->first()->processingResults->last()->processingDateTime);
    }

    /** @test */
    public function getAllHandledReturns()
    {
        $this->useMock('200-get-handled-returns.json');

        $returns = $this->client->returns->list(null, true);
        $this->assertInstanceOf(Collection::class, $returns);
        $this->assertCount(3, $returns);
        $this->assertInstanceOf(Returns::class, $returns->first());
        $this->assertNotNull($returns->first()->returnId);
        $this->assertSame('15897410', $returns->first()->returnId);
        $this->assertInstanceOf(\DateTime::class, $returns->first()->registrationDateTime);
        $this->assertSame('FBR', $returns->first()->fulfilmentMethod);
        $this->assertInstanceOf(Collection::class, $returns->first()->returnItems);
        $this->assertInstanceOf(Returns\Item::class, $returns->first()->returnItems->first());
        $this->assertSame('60283607', $returns->first()->returnItems->first()->rmaId);
        $this->assertSame('1044796550', $returns->first()->returnItems->first()->orderId);
        $this->assertSame('0634154562079', $returns->first()->returnItems->first()->ean);
        $this->assertSame(1, $returns->first()->returnItems->first()->expectedQuantity);
        $this->assertInstanceOf(Returns\ReturnReason::class, $returns->first()->returnItems->first()->returnReason);
        $this->assertSame('Verkeerde maat of formaat', $returns->first()->returnItems->first()->returnReason->mainReason);
        $this->assertSame('Verkeerde maat of formaat', $returns->first()->returnItems->first()->returnReason->customerComments);
        $this->assertSame(true, $returns->first()->returnItems->first()->handled);
        // assert multiple items
        $this->assertInstanceOf(Collection::class, $returns->get(1)->returnItems);
        $this->assertCount(2, $returns->get(1)->returnItems);
        $this->assertInstanceOf(Returns\Item::class, $returns->get(1)->returnItems->first());
        // first return item
        $this->assertInstanceOf(Returns\Item::class, $returns->get(1)->returnItems->first());
        $this->assertSame('60282944', $returns->get(1)->returnItems->first()->rmaId);
        $this->assertSame('1043965710', $returns->get(1)->returnItems->first()->orderId);
        $this->assertSame('0811571016532', $returns->get(1)->returnItems->first()->ean);
        $this->assertSame(1, $returns->get(1)->returnItems->first()->expectedQuantity);
        $this->assertInstanceOf(Returns\ReturnReason::class, $returns->get(1)->returnItems->first()->returnReason);
        $this->assertSame('Verkeerd artikel ontvangen', $returns->get(1)->returnItems->first()->returnReason->mainReason);
        $this->assertSame('Verkeerd artikel ontvangen', $returns->get(1)->returnItems->first()->returnReason->customerComments);
        $this->assertSame(true, $returns->get(1)->returnItems->first()->handled);
        // second return item
        $this->assertInstanceOf(Returns\Item::class, $returns->get(1)->returnItems->last());
        $this->assertSame('60282945', $returns->get(1)->returnItems->last()->rmaId);
        $this->assertSame('1044194100', $returns->get(1)->returnItems->last()->orderId);
        $this->assertSame('3138520283072', $returns->get(1)->returnItems->last()->ean);
        $this->assertSame(1, $returns->get(1)->returnItems->last()->expectedQuantity);
        $this->assertInstanceOf(Returns\ReturnReason::class, $returns->get(1)->returnItems->last()->returnReason);
        $this->assertSame('Artikel is defect/werkt niet', $returns->get(1)->returnItems->last()->returnReason->mainReason);
        $this->assertSame('Artikel is defect/werkt niet', $returns->get(1)->returnItems->last()->returnReason->customerComments);
        $this->assertSame(true, $returns->get(1)->returnItems->last()->handled);
    }

    /** @test */
    public function getReturnById()
    {
        $id = 15892026;
        $this->useMock('200-get-return-by-id.json');

        $return = $this->client->returns->get($id);
        $this->assertInstanceOf(Returns::class, $return);
        $this->assertNotNull($return->returnId);
        $this->assertSame('15892026', $return->returnId);
        $this->assertInstanceOf(\DateTime::class, $return->registrationDateTime);
        $this->assertSame('FBR', $return->fulfilmentMethod);
        $this->assertInstanceOf(Collection::class, $return->returnItems);
        $this->assertInstanceOf(Returns\Item::class, $return->returnItems->first());
        $this->assertSame('60278123', $return->returnItems->first()->rmaId);
        $this->assertSame('1020824520', $return->returnItems->first()->orderId);
        $this->assertSame('5702015866736', $return->returnItems->first()->ean);
        $this->assertSame(1, $return->returnItems->first()->expectedQuantity);
        $this->assertInstanceOf(Returns\ReturnReason::class, $return->returnItems->first()->returnReason);
        $this->assertSame('Geen reden', $return->returnItems->first()->returnReason->mainReason);
        $this->assertSame(true, $return->returnItems->first()->handled);
        $this->assertInstanceOf(Collection::class, $return->returnItems->first()->processingResults);
        $this->assertCount(1, $return->returnItems->first()->processingResults);

        $this->assertSame(1, $return->returnItems->first()->processingResults->first()->quantity);
        $this->assertSame('ACCEPTED', $return->returnItems->first()->processingResults->first()->processingResult);
        $this->assertSame('RETURN_RECEIVED', $return->returnItems->first()->processingResults->first()->handlingResult);
        $this->assertInstanceOf(\DateTime::class, $return->returnItems->first()->processingResults->first()->processingDateTime);
    }

    /** @test */
    public function getReturnByIdWithMultipleReturns()
    {
        $id = 15897410;
        $this->useMock('200-get-return-by-id-multiple-return-items.json');

        $return = $this->client->returns->get($id);
        $this->assertInstanceOf(Returns::class, $return);
        $this->assertNotNull($return->returnId);
        $this->assertSame('15897410', $return->returnId);
        $this->assertInstanceOf(\DateTime::class, $return->registrationDateTime);
        $this->assertSame('FBR', $return->fulfilmentMethod);
        $this->assertInstanceOf(Collection::class, $return->returnItems);
        $this->assertCount(2, $return->returnItems);
        // first return
        $this->assertInstanceOf(Returns\Item::class, $return->returnItems->first());
        $this->assertSame('60283607', $return->returnItems->first()->rmaId);
        $this->assertSame('1044796550', $return->returnItems->first()->orderId);
        $this->assertSame('0634154562079', $return->returnItems->first()->ean);
        $this->assertSame(1, $return->returnItems->first()->expectedQuantity);
        $this->assertInstanceOf(Returns\ReturnReason::class, $return->returnItems->first()->returnReason);
        $this->assertSame('Verkeerde maat of formaat', $return->returnItems->first()->returnReason->mainReason);
        $this->assertSame('Verkeerde maat of afmeting > Te klein', $return->returnItems->first()->returnReason->customerComments);
        $this->assertSame(true, $return->returnItems->first()->handled);
        $this->assertInstanceOf(Collection::class, $return->returnItems->first()->processingResults);
        $this->assertCount(1, $return->returnItems->first()->processingResults);
        $this->assertSame(1, $return->returnItems->first()->processingResults->first()->quantity);
        $this->assertSame('ACCEPTED', $return->returnItems->first()->processingResults->first()->processingResult);
        $this->assertSame('RETURN_RECEIVED', $return->returnItems->first()->processingResults->first()->handlingResult);
        $this->assertInstanceOf(\DateTime::class, $return->returnItems->first()->processingResults->first()->processingDateTime);
        // 2nd return
        $this->assertInstanceOf(Returns\Item::class, $return->returnItems->last());
        $this->assertSame('60283608', $return->returnItems->last()->rmaId);
        $this->assertSame('1044796550', $return->returnItems->last()->orderId);
        $this->assertSame('0634154562956', $return->returnItems->last()->ean);
        $this->assertSame(1, $return->returnItems->last()->expectedQuantity);
        $this->assertInstanceOf(Returns\ReturnReason::class, $return->returnItems->last()->returnReason);
        $this->assertSame('Anders, namelijk:', $return->returnItems->last()->returnReason->mainReason);
        $this->assertSame('Ik heb me bedacht', $return->returnItems->last()->returnReason->customerComments);
        $this->assertSame(false, $return->returnItems->last()->handled);
        $this->assertNull($return->returnItems->last()->processingResults);
    }

    /** @test */
    public function createReturn()
    {
        $this->useMock('200-create-return.json');

        $orderItemId = '1044796550';
        $quantity = 1;
        $state = ReturnResultTypes::RETURN_RECEIVED;

        $status = $this->client->returns->create($orderItemId, $quantity, $state);
        $this->assertInstanceOf(ProcessStatus::class, $status);
        $this->assertSame('1', $status->processStatusId);
        $this->assertSame('CREATE_RETURN_ITEM', $status->eventType);
        $this->assertSame('PENDING', $status->status);
        $this->assertInstanceOf(Collection::class, $status->links);
        $this->assertInstanceOf(ProcessStatus\Link::class, $status->links->first());
    }

    /** @test */
    public function createPickupReplenishment()
    {
        $this->useMock('200-create-replenishment-pickup.json');

        $replenishment = new Replenishment([
            'reference' => 'unittest002',
            'pickupAppointment' => new Replenishment\PickupAppointment([
                'address' => new Address([
                    'streetName' => 'Utrechtseweg',
                    'houseNumber' => 99,
                    'zipCode' => '3702 AA',
                    'city' => 'Zeist',
                    'countryCode' => 'NL',
                    'attentionOf' => 'Station'
                ]),
                'pickupTimeSlot' => new Replenishment\PickupTimeslot([
                    'fromDateTime' => '2024-01-21 09:30:00',
                    'untilDateTime' => '2024-01-21 11:30:00'
                ]),
                'commentToTransporter' => 'Custom reference'
            ]),
            'labelingByBol' => true,
            'numberOfLoadCarriers' => 1,
            'lines' => [
                [
                    'ean' => '0846127026185',
                    'quantity' => 1
                ]
            ]
        ]);
        $status = $this->client->replenishments->create($replenishment);
        $this->assertInstanceOf(ProcessStatus::class, $status);
        $this->assertSame('1', $status->processStatusId);
        $this->assertSame('CREATE_REPLENISHMENT', $status->eventType);
        $this->assertSame('PENDING', $status->status);
        $this->assertInstanceOf(Collection::class, $status->links);
        $this->assertInstanceOf(ProcessStatus\Link::class, $status->links->first());
    }

    /** @test */
    public function getPickupTimeSlots()
    {
        $this->useMock('200-get-replenishments-pickup-timeslots.json');

        $address = new Address([
            'streetName' => 'Utrechtseweg',
            'houseNumber' => 99,
            'houseNumberExtension' => 'A',
            'zipCode' => '3702 AA',
            'city' => 'Zeist',
            'countryCode' => 'NL',
        ]);
        $numberOfLoadCarriers = 2;

        $timeslots = $this->client->replenishments->pickupTimeslots($address, $numberOfLoadCarriers);
        $this->assertInstanceOf(Collection::class, $timeslots);
        $this->assertCount(7, $timeslots);
        $this->assertInstanceOf(Replenishment\PickupTimeslot::class, $timeslots->first());
        $this->assertInstanceOf(\DateTime::class, $timeslots->first()->fromDateTime);
        $this->assertInstanceOf(\DateTime::class, $timeslots->first()->untilDateTime);
    }

    /** @test */
    public function getProductLabels()
    {
        $products = [
            ['ean' => '0846127026185', 'quantity' => 5],
            ['ean' => '8716393000627', 'quantity' => 2]
        ];

        $labels = $this->client->replenishments->productLabels($products, LabelFormat::AVERY_J8159);

        $this->assertInstanceOf(Replenishment\ProductLabels::class, $labels);
    }

    /** @test */
    public function updateReplenishment()
    {
        $this->useMock('200-update-replenishment.json');

        $replenishment = new Replenishment([
            'replenishmentId' => '2312188192',
            'deliveryInformation' => new Replenishment\DeliveryInformation([
                'expectedDeliveryDate' => '2024-01-29'
            ])
        ]);
        $status = $this->client->replenishments->update($replenishment);
        $this->assertInstanceOf(ProcessStatus::class, $status);
        $this->assertSame('1', $status->processStatusId);
        $this->assertSame('UPDATE_REPLENISHMENT', $status->eventType);
        $this->assertSame('PENDING', $status->status);
        $this->assertInstanceOf(Collection::class, $status->links);
        $this->assertInstanceOf(ProcessStatus\Link::class, $status->links->first());
    }

    /** @test */
    public function getLoadCarrierLabels()
    {
        $this->markTestSkipped('Test data provided by bol returns a replenishment not found...');
//        $this->useMock('200-update-replenishment.json');

        $id = '4220489554';
        $labelType = 'TRANSPORT';
        $label = $this->client->replenishments->loadCarrierLabels($id, $labelType);
    }

    /** @test */
    public function getPicklist()
    {
        $id = '2312208179';

        $response = $this->client->replenishments->picklist($id);
        $this->assertInstanceOf(Replenishment\Picklist::class, $response);
        $this->assertSame($id, $response->id);
    }

    /** @test */
    public function invalidStateThrowsAnException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid state');
        $this->client->replenishments->list(null, null, null, null, 'invalid');
    }
}
