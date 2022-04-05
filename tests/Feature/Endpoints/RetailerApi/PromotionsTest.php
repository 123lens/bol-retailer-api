<?php
namespace Budgetlens\BolRetailerApi\Tests\Feature\Endpoints\RetailerApi;

use Budgetlens\BolRetailerApi\Resources\Commission;
use Budgetlens\BolRetailerApi\Resources\Country;
use Budgetlens\BolRetailerApi\Resources\Promotion;
use Budgetlens\BolRetailerApi\Resources\Promotions\Campaign;
use Budgetlens\BolRetailerApi\Resources\Promotions\Product;
use Budgetlens\BolRetailerApi\Resources\Promotions\RelevanceScore;
use Budgetlens\BolRetailerApi\Resources\Reduction;
use Budgetlens\BolRetailerApi\Tests\TestCase;
use Illuminate\Support\Collection;

class PromotionsTest extends TestCase
{
    /** @test */
    public function getPromotions()
    {
        $this->useMock('200-get-promotions.json');

        $promotions = $this->client->promotions->list([
            'PRICE_OFF',
            'AWARENESS'
        ]);
        $this->assertInstanceOf(Collection::class, $promotions);
        $this->assertCount(2, $promotions);
        $this->assertInstanceOf(Promotion::class, $promotions->first());
        $this->assertSame('544289', $promotions->first()->promotionId);
        $this->assertSame('FuturePromoTest', $promotions->first()->title);
        $this->assertInstanceOf(\DateTime::class, $promotions->first()->startDateTime);
        $this->assertInstanceOf(\DateTime::class, $promotions->first()->endDateTime);
        $this->assertInstanceOf(Collection::class, $promotions->first()->countries);
        $this->assertCount(2, $promotions->first()->countries);
        $this->assertInstanceOf(Country::class, $promotions->first()->countries->first());
        $this->assertSame('NL', $promotions->first()->countries->first()->countryCode);
        $this->assertSame('AWARENESS', $promotions->first()->promotionType);
        $this->assertInstanceOf(Campaign::class, $promotions->first()->campaign);
        $this->assertSame('Deals', $promotions->first()->campaign->name);
        $this->assertInstanceOf(\DateTime::class, $promotions->first()->campaign->startDateTime);
    }

    /** @test */
    public function getPromotionById()
    {
        $this->useMock('200-get-promotion-by-id.json');

        $id = 533736;
        $promotion = $this->client->promotions->get($id);
        $this->assertInstanceOf(Promotion::class, $promotion);
        $this->assertSame('533736', $promotion->promotionId);
        $this->assertSame('Test Promotion', $promotion->title);
        $this->assertInstanceOf(\DateTime::class, $promotion->startDateTime);
        $this->assertInstanceOf(\DateTime::class, $promotion->endDateTime);
        $this->assertInstanceOf(Collection::class, $promotion->countries);
        $this->assertInstanceOf(Country::class, $promotion->countries->first());
        $this->assertSame('NL', $promotion->countries->first()->countryCode);
        $this->assertSame('PRICE_OFF', $promotion->promotionType);
        $this->assertInstanceOf(Campaign::class, $promotion->campaign);
        $this->assertSame('Deals', $promotion->campaign->name);
        $this->assertInstanceOf(\DateTime::class, $promotion->campaign->startDateTime);
        $this->assertInstanceOf(\DateTime::class, $promotion->campaign->endDateTime);
    }

    /** @test */
    public function getPromotionProducts()
    {
        $this->useMock('200-get-promotion-products.json');

        $id = 544860;
        $products = $this->client->promotions->products($id);
        $this->assertInstanceOf(Collection::class, $products);
        $this->assertInstanceOf(Product::class, $products->first());
        $this->assertSame('4005808940127', $products->first()->ean);
        $this->assertInstanceOf(Collection::class, $products->first()->relevanceScores);
        $this->assertCount(2, $products->first()->relevanceScores);
        $this->assertInstanceOf(RelevanceScore::class, $products->first()->relevanceScores->first());
        $this->assertSame("NL", $products->first()->relevanceScores->first()->countryCode);
        $this->assertSame(13, $products->first()->relevanceScores->first()->relevanceScore);
        $this->assertSame(13.76, $products->first()->maximumPrice);
    }
}
