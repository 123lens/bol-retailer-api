<?php
namespace Budgetlens\BolRetailerApi\Tests\Feature\Endpoints;

use Budgetlens\BolRetailerApi\Exceptions\BolRetailerException;
use Budgetlens\BolRetailerApi\Resources\Address;
use Budgetlens\BolRetailerApi\Resources\Fulfilment;
use Budgetlens\BolRetailerApi\Resources\Offer;
use Budgetlens\BolRetailerApi\Resources\Order;
use Budgetlens\BolRetailerApi\Resources\ProcessStatus;
use Budgetlens\BolRetailerApi\Resources\Product;
use Budgetlens\BolRetailerApi\Tests\TestCase;

class OffersTest extends TestCase
{
    /** @test */
    public function createNewFbbOffer()
    {
        $this->useMock('200-create-offer-fbb.json');

        $offer = new Offer([
            'ean' => '0000007740404',
            'condition' => 'NEW',
            'reference' => 'unit-test',
            'onHoldByRetailer' => true,
            'unknownProductTitle' => 'unit-test',
            'price' => 9999,
            'stock' => 0,
            'fulfilment' => 'FBB'
        ]);
        $status = $this->client->offers->create($offer);
        $this->assertInstanceOf(ProcessStatus::class, $status);
        $this->assertSame(1, $status->id);
        $this->assertSame('CREATE_OFFER', $status->eventType);
        $this->assertSame('PENDING', $status->status);
    }


}
