<?php
namespace Budgetlens\BolRetailerApi\Tests\Feature\Endpoints;

use Budgetlens\BolRetailerApi\Client;
use Budgetlens\BolRetailerApi\Exceptions\BolRetailerException;
use Budgetlens\BolRetailerApi\Exceptions\ValidationException;
use Budgetlens\BolRetailerApi\Resources\Address;
use Budgetlens\BolRetailerApi\Resources\Condition;
use Budgetlens\BolRetailerApi\Resources\Fulfilment;
use Budgetlens\BolRetailerApi\Resources\Offer;
use Budgetlens\BolRetailerApi\Resources\Order;
use Budgetlens\BolRetailerApi\Resources\Pricing;
use Budgetlens\BolRetailerApi\Resources\ProcessStatus;
use Budgetlens\BolRetailerApi\Resources\Product;
use Budgetlens\BolRetailerApi\Resources\Stock;
use Budgetlens\BolRetailerApi\Resources\Store;
use Budgetlens\BolRetailerApi\Tests\TestCase;
use Illuminate\Support\Collection;

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
            'price' => 99.99,
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
    public function createNewFbrOffer()
    {
        $offer = new Offer([
            'ean' => '0000007740404',
            'condition' => 'NEW',
            'reference' => 'unit-test',
            'onHoldByRetailer' => true,
            'unknownProductTitle' => 'unit-test',
            'price' => 99.99,
            'stock' => 0,
            'fulfilment' => 'FBR'
        ]);
        $status = $this->client->offers->create($offer);
        $this->assertInstanceOf(ProcessStatus::class, $status);
        $this->assertSame(1, $status->id);
        $this->assertSame('CREATE_OFFER', $status->eventType);
        $this->assertSame('PENDING', $status->status);
    }

    /** @test */
    public function updateOffer()
    {
        $offer = new Offer([
            'offerId' => '13722de8-8182-d161-5422-4a0a1caab5c8',
            'onHoldByRetailer' => false,
            'fulfilment' => 'FBR'
        ]);
        $status = $this->client->offers->update($offer);

        $this->assertInstanceOf(ProcessStatus::class, $status);
        $this->assertSame(1, $status->id);
        $this->assertSame('UPDATE_OFFER', $status->eventType);
        $this->assertSame('PENDING', $status->status);
    }

    /** @test */
    public function deleteOffer()
    {
        $status = $this->client->offers->delete('13722de8-8182-d161-5422-4a0a1caab5c8');

        $this->assertInstanceOf(ProcessStatus::class, $status);
        $this->assertSame(1, $status->id);
        $this->assertSame('DELETE_OFFER', $status->eventType);
        $this->assertSame('PENDING', $status->status);
    }

    /** @test */
    public function deleteOfferWithResource()
    {
        $offer = new Offer([
            'offerId' => '13722de8-8182-d161-5422-4a0a1caab5c8'
        ]);

        $status = $this->client->offers->delete($offer);

        $this->assertInstanceOf(ProcessStatus::class, $status);
        $this->assertSame(1, $status->id);
        $this->assertSame('DELETE_OFFER', $status->eventType);
        $this->assertSame('PENDING', $status->status);
    }

    /** @test */
    public function getOfferById()
    {
        $offer = $this->client->offers->get('13722de8-8182-d161-5422-4a0a1caab5c8');
        $this->assertInstanceOf(Offer::class, $offer);
        $this->assertSame('3165140085229', $offer->ean);
        $this->assertSame('02224499', $offer->reference);
        $this->assertInstanceOf(Condition::class, $offer->condition);
        $this->assertSame('NEW', $offer->condition->name);
        $this->assertInstanceOf(Pricing::class, $offer->pricing);
        $this->assertSame(4499, $offer->pricing->bundlePrices->first()->unitPrice);
        $this->assertSame(1, $offer->pricing->bundlePrices->first()->quantity);
        $this->assertSame(3999, $offer->pricing->bundlePrices->last()->unitPrice);
        $this->assertSame(12, $offer->pricing->bundlePrices->last()->quantity);
        $this->assertInstanceOf(Stock::class, $offer->stock);
        $this->assertSame(3, $offer->stock->amount);
        $this->assertSame(false, $offer->stock->managedByRetailer);
        $this->assertInstanceOf(Fulfilment::class, $offer->fulfilment);
        $this->assertSame('FBR', $offer->fulfilment->method);
        $this->assertSame('24uurs-15', $offer->fulfilment->deliveryCode);
        $this->assertInstanceOf(Store::class, $offer->store);
        $this->assertSame('Bosch Waterpomp voor boormachine 2500 L/M', $offer->store->productTitle);
    }

    /** @test */
    public function getOfferByResource()
    {
        $offerResource = new Offer([
            'offerId' => '13722de8-8182-d161-5422-4a0a1caab5c8'
        ]);

        $offer = $this->client->offers->get($offerResource);
        $this->assertInstanceOf(Offer::class, $offer);
        $this->assertSame('3165140085229', $offer->ean);
        $this->assertSame('02224499', $offer->reference);
        $this->assertInstanceOf(Condition::class, $offer->condition);
        $this->assertSame('NEW', $offer->condition->name);
        $this->assertInstanceOf(Pricing::class, $offer->pricing);
        $this->assertSame(4499, $offer->pricing->bundlePrices->first()->unitPrice);
        $this->assertSame(1, $offer->pricing->bundlePrices->first()->quantity);
        $this->assertSame(3999, $offer->pricing->bundlePrices->last()->unitPrice);
        $this->assertSame(12, $offer->pricing->bundlePrices->last()->quantity);
        $this->assertInstanceOf(Stock::class, $offer->stock);
        $this->assertSame(3, $offer->stock->amount);
        $this->assertSame(false, $offer->stock->managedByRetailer);
        $this->assertInstanceOf(Fulfilment::class, $offer->fulfilment);
        $this->assertSame('FBR', $offer->fulfilment->method);
        $this->assertSame('24uurs-15', $offer->fulfilment->deliveryCode);
        $this->assertInstanceOf(Store::class, $offer->store);
        $this->assertSame('Bosch Waterpomp voor boormachine 2500 L/M', $offer->store->productTitle);
    }

    /** @test */
    public function updatePricing()
    {
        $offer = new Offer([
            'offerId' => '13722de8-8182-d161-5422-4a0a1caab5c8',
            'pricing' => new Pricing([
                'bundlePrices' => [
                    ['quantity' => 1, 'unitPrice' => 99.99],
                    ['quantity' => 2, 'unitPrice' => 89.99],
                    ['quantity' => 3, 'unitPrice' => 85.99]
                ]
            ])
        ]);
        $status = $this->client->offers->updatePrice($offer);

        $this->assertInstanceOf(ProcessStatus::class, $status);
        $this->assertSame(1, $status->id);
        $this->assertSame('UPDATE_OFFER_PRICE', $status->eventType);
        $this->assertSame('PENDING', $status->status);
    }


    /** @test */
    public function updateStock()
    {
        $offer = new Offer([
            'offerId' => '13722de8-8182-d161-5422-4a0a1caab5c8',
            'stock' => new Stock([
                'amount' => 100
            ])
        ]);
        $status = $this->client->offers->updateStock($offer);

        $this->assertInstanceOf(ProcessStatus::class, $status);
        $this->assertSame(1, $status->id);
        $this->assertSame('UPDATE_OFFER_STOCK', $status->eventType);
        $this->assertSame('PENDING', $status->status);
    }


    /** @test */
    public function requestOffersExport()
    {
        $status = $this->client->offers->requestExport();
        $this->assertInstanceOf(ProcessStatus::class, $status);
        $this->assertSame(1, $status->id);
        $this->assertSame('CREATE_OFFER_EXPORT', $status->eventType);
        $this->assertSame('PENDING', $status->status);
    }

    /** @test */
    public function getOffersExport()
    {
        $this->useMock('200-offers-export.csv', 200, ['Content-Type' => 'application/vnd.retailer.v4+csv;charset=UTF-8']);
        $offers = $this->client->offers->getExport('offer-export-id');

        $this->assertInstanceOf(Collection::class, $offers);
        $this->assertCount(10, $offers);
        $this->assertInstanceOf(Offer::class, $offers->first());
        $this->assertSame('offer-id-1', $offers->first()->offerId);
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
            'price' => 99.99,
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
