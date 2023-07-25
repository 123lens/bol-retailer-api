<?php

namespace Budgetlens\BolRetailerApi\Tests\Feature\Endpoints\RetailerApi;

use Budgetlens\BolRetailerApi\Resources\Retailer;
use Budgetlens\BolRetailerApi\Tests\TestCase;

class RetailersTest extends TestCase
{
    /** @test */
    public function getRetailerById()
    {
        $this->useMock('200-get-retailer-by-id.json');

        $retailerId = "8493657";
        $retailer = $this->client->retailers->get($retailerId);

        $this->assertInstanceOf(Retailer::class, $retailer);
        $this->assertSame($retailerId, $retailer->retailerId);
        $this->assertSame('RETAILER123', $retailer->displayName);
        $this->assertInstanceOf(\DateTimeImmutable::class, $retailer->registrationDate);
        $this->assertSame('2022-06-17', $retailer->registrationDate->format('Y-m-d'));
        $this->assertSame(false, $retailer->topRetailer);
        $this->assertSame('THREE_MONTHS', $retailer->ratingMethod);
        $this->assertInstanceOf(Retailer\RetailerRating::class, $retailer->retailerRating);
        $this->assertSame(9.1, $retailer->retailerRating->retailerRating);
        $this->assertSame(9.6, $retailer->retailerRating->productInformationRating);
        $this->assertSame(9.4, $retailer->retailerRating->deliveryTimeRating);
        $this->assertSame(9.4, $retailer->retailerRating->shippingRating);
        $this->assertInstanceOf(Retailer\RetailerReview::class, $retailer->retailerReview);
        $this->assertSame(14, $retailer->retailerReview->totalReviewCount);
        $this->assertSame(88, $retailer->retailerReview->approvalPercentage);
        $this->assertSame(13, $retailer->retailerReview->positiveReviewCount);
        $this->assertSame(1, $retailer->retailerReview->neutralReviewCount);
        $this->assertSame(0, $retailer->retailerReview->negativeReviewCount);
    }
}
