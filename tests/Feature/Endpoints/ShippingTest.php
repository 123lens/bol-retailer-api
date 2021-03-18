<?php
namespace Budgetlens\BolRetailerApi\Tests\Feature\Endpoints;

use Budgetlens\BolRetailerApi\Exceptions\BolRetailerException;
use Budgetlens\BolRetailerApi\Exceptions\ValidationException;
use Budgetlens\BolRetailerApi\Resources\Address;
use Budgetlens\BolRetailerApi\Resources\DeliveryOption;
use Budgetlens\BolRetailerApi\Resources\Fulfilment;
use Budgetlens\BolRetailerApi\Resources\Offer;
use Budgetlens\BolRetailerApi\Resources\Order;
use Budgetlens\BolRetailerApi\Resources\ProcessStatus;
use Budgetlens\BolRetailerApi\Resources\ProcessStatusCollection;
use Budgetlens\BolRetailerApi\Resources\Product;
use Budgetlens\BolRetailerApi\Tests\TestCase;
use Illuminate\Support\Collection;

class ShippingTest extends TestCase
{
    /** @test */
    public function getDeliveryOptions()
    {
        $order = new Order([
            'orderItems' => [
                [
                    'orderItemId' => 2095052647
                ]
            ]
        ]);
        $options = $this->client->shipping->getDeliveryOptions($order);
        $this->assertInstanceOf(Collection::class, $options);
        $this->assertCount(2, $options);
        $this->assertInstanceOf(DeliveryOption::class, $options->first());
        $this->assertSame('32c4a88c-3c64-41ad-83a1-24450b341747', $options->first()->shippingLabelOfferId);
        $this->assertSame('PARCEL', $options->first()->labelType);
        $this->assertSame(441, $options->first()->labelPrice->totalPrice);
        $this->assertSame('10 kg', $options->first()->packageRestrictions->maxWeight);
        $this->assertSame(true, $options->first()->handoverDetails->meetsCustomerExpectation);
        $this->assertInstanceOf(\DateTime::class, $options->first()->handoverDetails->latestHandoverDateTime);
    }
}
