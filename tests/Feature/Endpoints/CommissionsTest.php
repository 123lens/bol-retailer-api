<?php
namespace Budgetlens\BolRetailerApi\Tests\Feature\Endpoints;

use Budgetlens\BolRetailerApi\Client;
use Budgetlens\BolRetailerApi\Resources\Inbound;
use Budgetlens\BolRetailerApi\Resources\InboundPackinglist;
use Budgetlens\BolRetailerApi\Resources\InboundProductLabels;
use Budgetlens\BolRetailerApi\Resources\InboundShippingLabel;
use Budgetlens\BolRetailerApi\Resources\ProcessStatus;
use Budgetlens\BolRetailerApi\Resources\Timeslot;
use Budgetlens\BolRetailerApi\Resources\Transporter;
use Budgetlens\BolRetailerApi\Tests\TestCase;
use Budgetlens\BolRetailerApi\Types\LabelFormat;
use Cassandra\Date;
use Illuminate\Support\Collection;

class CommissionsTest extends TestCase
{
    /** @test */
    public function getBulkCommissions()
    {
//        $this->useMock('200-get-inbounds.json');

        $items = [
            [
                "ean" => "8712626055150",
                "condition" => "NEW",
                "unitPrice" => "34.99"
            ], [
                "ean" => "8804269223123",
                "condition" => "NEW",
                "unitPrice" => "699.95"
            ], [
                "ean" => "8712626055143",
                "condition" => "GOOD",
                "unitPrice" => "24.50"
            ], [
                "ean" => "0604020064587",
                "condition" => "NEW",
                "unitPrice" => "24.95"
            ], [
                "ean" => "8718526069334",
                "condition" => "NEW",
                "unitPrice" => "25.00"
            ]
        ];

        $commissions = $this->client->commission->list($items);

        $this->assertInstanceOf(Collection::class, $inbounds);
        $this->assertCount(1, $inbounds);
        $this->assertInstanceOf(Inbound::class, $inbounds->first());
        $this->assertNotNull($inbounds->first()->inboundId);
        $this->assertSame(5850051250, $inbounds->first()->inboundId);
        $this->assertSame('ZENDINGLVB1GVR', $inbounds->first()->reference);
        $this->assertInstanceOf(\DateTime::class, $inbounds->first()->creationDateTime);
        $this->assertInstanceOf(Timeslot::class, $inbounds->first()->timeSlot);
    }

    /** @test */
    public function getInboundById()
    {
        $this->useMock('200-get-inbound-by-id.json');
        $id = '5850051250';

        $inbound = $this->client->inbounds->get($id);
        $this->assertInstanceOf(Inbound::class, $inbound);
        $this->assertNotNull($inbound->inboundId);
        $this->assertSame(5850051250, $inbound->inboundId);
        $this->assertSame('ZENDINGLVB1GVR', $inbound->reference);
        $this->assertInstanceOf(\DateTime::class, $inbound->creationDateTime);
        $this->assertInstanceOf(Timeslot::class, $inbound->timeSlot);
    }

    /** @test */
    public function createInbound()
    {
        $this->useMock('200-create-inbound.json');

        $inbound = new Inbound([
            'reference' => 'unit-test',
            'timeSlot' => new Timeslot([
                'startDateTime' => new \DateTime('2018-04-05 12:00:00'),
                'endDateTime' => new \DateTime('2018-04-05 17:00:00')
            ]),
            'inboundTransporter' => new Transporter([
                'name' => 'PostNL',
                'code' => 'PostNL'
            ]),
            'labellingService' => false,
            'products' => [
                ['ean' => '8718526069331', 'announcedQuantity' => 1],
                ['ean' => '8718526069332', 'announcedQuantity' => 2],
                ['ean' => '8718526069333', 'announcedQuantity' => 3],
                ['ean' => '8718526069334', 'announcedQuantity' => 4]
            ]
        ]);

        $status = $this->client->inbounds->create($inbound);

        $this->assertInstanceOf(ProcessStatus::class, $status);
        $this->assertSame(1, $status->id);
        $this->assertSame('CREATE_INBOUND', $status->eventType);
        $this->assertSame('PENDING', $status->status);
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
