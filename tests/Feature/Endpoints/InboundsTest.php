<?php
namespace Budgetlens\BolRetailerApi\Tests\Feature\Endpoints;

use Budgetlens\BolRetailerApi\Resources\Inbound;
use Budgetlens\BolRetailerApi\Resources\Timeslot;
use Budgetlens\BolRetailerApi\Tests\TestCase;
use Cassandra\Date;
use Illuminate\Support\Collection;

class InboundsTest extends TestCase
{
    /** @test */
    public function getAllInbounds()
    {
        $this->useMock('200-get-inbounds.json');

        $inbounds = $this->client->inbounds->list();

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
    public function getDeliveryWindows()
    {
        $deliveryWindows = $this->client->inbounds->getDeliveryWindows(new \DateTime('2018-05-31'));
        $this->assertInstanceOf(Collection::class, $deliveryWindows);
        $this->assertCount(24, $deliveryWindows);
        $this->assertInstanceOf(Timeslot::class, $deliveryWindows->first());
        $this->assertInstanceOf(\DateTime::class, $deliveryWindows->first()->startDateTime);
        $this->assertInstanceOf(\DateTime::class, $deliveryWindows->first()->endDateTime);
        $this->assertNotNull($deliveryWindows->first()->startDateTime);
        $this->assertNotNull($deliveryWindows->first()->endDateTime);
    }
}
