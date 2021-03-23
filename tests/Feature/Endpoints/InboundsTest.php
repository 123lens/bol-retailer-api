<?php
namespace Budgetlens\BolRetailerApi\Tests\Feature\Endpoints;

use Budgetlens\BolRetailerApi\Resources\Inbound;
use Budgetlens\BolRetailerApi\Tests\TestCase;
use Illuminate\Support\Collection;

class InboundsTest extends TestCase
{
    /** @test */
    public function getAllInbounds()
    {
//        $this->useMock('200-get-inventory.json');

        $inbounds = $this->client->inbounds->list();

        $this->assertInstanceOf(Collection::class, $inbounds);
        $this->assertCount(1, $inbounds);
        $this->assertInstanceOf(Inbound::class, $inbounds->first());
        $this->assertNotNull($inbounds->first()->inboundId);
        $this->assertSame(5850051250, $inbounds->first()->inboundId);
        $this->assertSame('ZENDINGLVB1GVR', $inbounds->first()->reference);
        $this->assertInstanceOf(\DateTime::class, $inbounds->first()->creationDateTime);
    }
}
