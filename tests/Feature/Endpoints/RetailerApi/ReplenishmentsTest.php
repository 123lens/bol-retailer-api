<?php

namespace Budgetlens\BolRetailerApi\Tests\Feature\Endpoints\RetailerApi;

use Budgetlens\BolRetailerApi\Resources\Address;
use Budgetlens\BolRetailerApi\Resources\ProcessStatus;
use Budgetlens\BolRetailerApi\Resources\ProductDestination;
use Budgetlens\BolRetailerApi\Resources\Replenishment;
use Budgetlens\BolRetailerApi\Tests\TestCase;
use Budgetlens\BolRetailerApi\Types\LabelFormat;
use Budgetlens\BolRetailerApi\Types\ReplenishState;
use Budgetlens\BolRetailerApi\Types\TransportState;
use Illuminate\Support\Collection;

class ReplenishmentsTest extends TestCase
{
    /** @test */
    public function getAllReplenishments()
    {
        $this->useMock('200-get-replenishments.json');

        $replenishments = $this->client->replenishments->list(
            null,
            null,
            null,
            null,
            ['ANNOUNCED'],
            1
        );
        $this->assertInstanceOf(Collection::class, $replenishments);
        $this->assertCount(2, $replenishments);
        $this->assertInstanceOf(Replenishment::class, $replenishments->first());
        $this->assertNotNull($replenishments->first()->replenishmentId);
        $this->assertSame('2312208180', $replenishments->first()->replenishmentId);
        $this->assertSame('MYREF02', $replenishments->first()->reference);
        $this->assertInstanceOf(\DateTime::class, $replenishments->first()->creationDateTime);
        $this->assertInstanceOf(Collection::class, $replenishments->first()->lines);
        $this->assertInstanceOf(Replenishment\Line::class, $replenishments->first()->lines->first());
        $this->assertCount(1, $replenishments->first()->lines);
        $this->assertSame('0846127026185', $replenishments->first()->lines->first()->ean);
        // validate invalid
        $this->assertInstanceOf(Collection::class, $replenishments->last()->lines);
        $this->assertInstanceOf(Replenishment\Line::class, $replenishments->last()->lines->first());
        $this->assertCount(2, $replenishments->last()->lines);
        $this->assertSame('UNKNOWN_FBB_PRODUCT', $replenishments->last()->invalidLines->first()->type);
    }

    /** @test */
    public function getReplenishmentById()
    {
        $this->useMock('200-get-replenishment.json');
        $id = '2312208179';

        $replenishment = $this->client->replenishments->get($id);
        $this->assertInstanceOf(Replenishment::class, $replenishment);
        $this->assertNotNull($replenishment->replenishmentId);
        $this->assertSame('2312208179', $replenishment->replenishmentId);
        $this->assertSame('MYREF01', $replenishment->reference);
        $this->assertInstanceOf(\DateTime::class, $replenishment->creationDateTime);
        $this->assertInstanceOf(Collection::class, $replenishment->lines);
        $this->assertInstanceOf(Replenishment\Line::class, $replenishment->lines->first());
        $this->assertCount(2, $replenishment->lines);
        $this->assertSame('8716393000627', $replenishment->lines->first()->ean);
        $this->assertInstanceOf(Collection::class, $replenishment->invalidLines);
        $this->assertInstanceOf(Replenishment\Line::class, $replenishment->invalidLines->first());
        $this->assertCount(1, $replenishment->invalidLines);
        $this->assertSame('UNKNOWN_FBB_PRODUCT', $replenishment->invalidLines->first()->type);
        $this->assertSame(true, $replenishment->labelingByBol);
        $this->assertSame(ReplenishState::ANNOUNCED, $replenishment->state);
        $this->assertInstanceOf(Replenishment\DeliveryInformation::class, $replenishment->deliveryInformation);
        $this->assertInstanceOf(\DateTime::class, $replenishment->deliveryInformation->expectedDeliveryDate);
        $this->assertSame('POSTNL', $replenishment->deliveryInformation->transporterCode);
        $this->assertInstanceOf(Replenishment\Warehouse::class, $replenishment->deliveryInformation->destinationWarehouse);
        $this->assertSame('Mechie Trommelenweg', $replenishment->deliveryInformation->destinationWarehouse->streetName);
        $this->assertSame('1', $replenishment->deliveryInformation->destinationWarehouse->houseNumber);
        $this->assertSame('5145ND', $replenishment->deliveryInformation->destinationWarehouse->zipCode);
        $this->assertSame('Waalwijk', $replenishment->deliveryInformation->destinationWarehouse->city);
        $this->assertSame('NL', $replenishment->deliveryInformation->destinationWarehouse->countryCode);
        $this->assertSame('t.a.v. bol.com', $replenishment->deliveryInformation->destinationWarehouse->attentionOf);
        $this->assertSame(2, $replenishment->numberOfLoadCarriers);
        $this->assertInstanceOf(Collection::class, $replenishment->loadCarriers);
        $this->assertInstanceOf(Replenishment\LoadCarrier::class, $replenishment->loadCarriers->first());
        $this->assertSame('020001200000007628', $replenishment->loadCarriers->first()->sscc);
        $this->assertSame(TransportState::ANNOUNCED, $replenishment->loadCarriers->first()->transportState);
        $this->assertInstanceOf(\DateTime::class, $replenishment->loadCarriers->first()->transportStateUpdateDateTime);
        $this->assertInstanceOf(Collection::class, $replenishment->stateTransitions);
        $this->assertInstanceOf(Replenishment\StateTransition::class, $replenishment->stateTransitions->first());
        $this->assertSame('ANNOUNCED', $replenishment->stateTransitions->first()->state);
        $this->assertInstanceOf(\DateTime::class, $replenishment->stateTransitions->first()->stateDateTime);
    }

    /** @test */
    public function getDeliveryDates()
    {
        $this->useMock('200-get-replenishment-delivery-dates.json');

        $result = $this->client->replenishments->deliveryDates();
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertInstanceOf(Replenishment\DeliveryDate::class, $result->first());
        $this->assertInstanceOf(\DateTime::class, $result->first()->date);
        $this->assertSame('2021-12-28', $result->first()->date->format('Y-m-d'));
    }

    /** @test */
    public function createReplenishment()
    {
        $this->useMock('200-create-replenishment.json');

        $replenishment = new Replenishment([
            'reference' => 'unittest001',
            'deliveryInformation' => new Replenishment\DeliveryInformation([
                'expectedDeliveryDate' => '2024-02-01',
                'transporterCode' => 'POSTNL'
            ]),
            'labelingByBol' => true,
            'numberOfLoadCarriers' => 2,
            'lines' => [
                [
                    'ean' => '0846127026185',
                    'quantity' => 5
                ],
                [
                    'ean' => '8716393000627',
                    'quantity' => 2
                ]
            ]
        ]);


        $status = $this->client->replenishments->create($replenishment);
        $this->assertInstanceOf(ProcessStatus::class, $status);
        $this->assertSame(1, $status->processStatusId);
        $this->assertSame('CREATE_REPLENISHMENT', $status->eventType);
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
        $this->assertSame(1, $status->processStatusId);
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
    public function canRequestProductDestinations()
    {
        $this->useMock('200-request-product-destinations.json');

        $status = $this->client->replenishments->productDestinations([
            '9781529105100',
            '9318478007195',
        ]);
        $this->assertInstanceOf(ProcessStatus::class, $status);
        $this->assertSame(1, $status->processStatusId);
        $this->assertSame('REQUEST_PRODUCT_DESTINATIONS', $status->eventType);
        $this->assertSame('PENDING', $status->status);
        $this->assertInstanceOf(\DateTime::class, $status->createTimestamp);
    }

    /** @test */
    public function canGetProductDestinations()
    {
        $this->useMock('200-get-product-destinations.json');

        $id = '6f0d7145-543e-4320-afb7-f43dd69b04dc';
        $destinations = $this->client->replenishments->getProductDestinations($id);
        $this->assertInstanceOf(Collection::class, $destinations);
        $this->assertInstanceOf(ProductDestination::class, $destinations->first());
        $this->assertInstanceOf(Replenishment\Warehouse::class, $destinations->first()->destinationWarehouse);
        $warehouse = $destinations->first()->destinationWarehouse;
        $this->assertSame('Mechie Trommelenweg', $warehouse->streetName);
        $this->assertSame(1, $warehouse->houseNumber);
        $this->assertSame('5145ND', $warehouse->zipCode);
        $this->assertSame('Waalwijk', $warehouse->city);
        $this->assertSame('NL', $warehouse->countryCode);
        $this->assertSame('t.a.v. bol.com', $warehouse->attentionOf);
        $this->assertInstanceOf(Collection::class, $destinations->first()->eans);
        $this->assertSame('9789077024485', $destinations->first()->eans->first());
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
        $this->assertSame(1, $status->processStatusId);
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
        $this->client->replenishments->list(null, null, null, null, ['invalid']);
    }
}
