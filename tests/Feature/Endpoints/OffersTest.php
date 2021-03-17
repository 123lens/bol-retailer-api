<?php
namespace Budgetlens\BolRetailerApi\Tests\Feature\Endpoints;

use Budgetlens\BolRetailerApi\Exceptions\BolRetailerException;
use Budgetlens\BolRetailerApi\Exceptions\ValidationException;
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

    /** @test */
    public function missingEancodeThrowsAValidationException()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Validation Failed, See violations');
        $offer = new Offer([
            'ean' => '0',
            'condition' => 'NEW',
            'reference' => 'unit-test',
            'onHoldByRetailer' => true,
            'unknownProductTitle' => 'unit-test',
            'price' => 9999,
            'stock' => 0,
            'fulfilment' => 'FBB'
        ]);
        try {
            $this->client->offers->create($offer);
        } catch (ValidationException $e) {
            $violations = $e->getViolations();
            $this->assertCount(1, $violations);
            $this->assertSame('ean', $violations->first()->name);
            $this->assertSame("Request contains invalid value(s): '0'.", $violations->first()->reason);
            throw $e;
        }
    }
}
