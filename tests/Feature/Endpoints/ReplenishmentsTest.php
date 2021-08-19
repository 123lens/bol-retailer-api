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
use Budgetlens\BolRetailerApi\Resources\Timeslot;
use Budgetlens\BolRetailerApi\Resources\Transporter;
use Budgetlens\BolRetailerApi\Tests\TestCase;
use Budgetlens\BolRetailerApi\Types\LabelFormat;
use Budgetlens\BolRetailerApi\Types\ReplenishState;
use Budgetlens\BolRetailerApi\Types\TransportState;
use Cassandra\Date;
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
            'ANNOUNCED',
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
        $this->assertSame('1', $status->processStatusId);
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
        $this->assertSame('1', $status->processStatusId);
        $this->assertSame('CREATE_REPLENISHMENT', $status->eventType);
        $this->assertSame('PENDING', $status->status);
        $this->assertInstanceOf(Collection::class, $status->links);
        $this->assertInstanceOf(ProcessStatus\Link::class, $status->links->first());
    }

    /** @test */
    public function getPickupTimeSlots()
    {
//        $this->useMock('200-create-replenishment-pickup.json');

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
    public function getPackingList()
    {
        $id = '5850051250';

        $inbound = $this->client->inbounds->getPackingList($id);

        $this->assertInstanceOf(InboundPackinglist::class, $inbound);
        $this->assertSame($id, $inbound->id);
    }

    /** @test */
    public function getShippingLabel()
    {
        $id = '5850051250';

        $inbound = $this->client->inbounds->getShippingLabel($id);
        $this->assertInstanceOf(InboundShippingLabel::class, $inbound);
        $this->assertSame($id, $inbound->id);
    }

    /** @test */
    public function getProductLabels()
    {
        $products = [
            ['ean' => '8717185945126', 'quantity' => 1],
            ['ean' => '8717185944747', 'quantity' => 2]
        ];

        $labels = $this->client->inbounds->getProductLabels($products, LabelFormat::ZEBRA_Z_PERFORM_1000T);

        $this->assertInstanceOf(InboundProductLabels::class, $labels);
    }
    /** @test */
    public function invalidStateThrowsAnException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid state');
        $this->client->inbounds->list(null, null, null, null, 'invalid');
    }

    /** @test */
    public function getDeliveryWindows()
    {
        $this->useMock('200-get-delivery-windows.json');

        $deliveryWindows = $this->client->inbounds->getDeliveryWindows(new \DateTime('2018-05-31'));
        $this->assertInstanceOf(Collection::class, $deliveryWindows);
        $this->assertCount(24, $deliveryWindows);
        $this->assertInstanceOf(Timeslot::class, $deliveryWindows->first());
        $this->assertInstanceOf(\DateTime::class, $deliveryWindows->first()->startDateTime);
        $this->assertInstanceOf(\DateTime::class, $deliveryWindows->first()->endDateTime);
        $this->assertNotNull($deliveryWindows->first()->startDateTime);
        $this->assertNotNull($deliveryWindows->first()->endDateTime);
    }

    /** @test */
    public function getTransporters()
    {
        $this->useMock('200-get-inbound-transporters.json');

        $transporters = $this->client->inbounds->getTransporters();
        $this->assertInstanceOf(Collection::class, $transporters);
        $this->assertCount(33, $transporters);
        $this->assertInstanceOf(Transporter::class, $transporters->first());
        $this->assertNotNull($transporters->first()->name);
        $this->assertNotNull($transporters->first()->code);
    }



}
