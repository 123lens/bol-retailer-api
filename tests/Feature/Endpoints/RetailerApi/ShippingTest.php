<?php

namespace Budgetlens\BolRetailerApi\Tests\Feature\Endpoints\RetailerApi;

use Budgetlens\BolRetailerApi\Resources\DeliveryOption;
use Budgetlens\BolRetailerApi\Resources\HandoverDetails;
use Budgetlens\BolRetailerApi\Resources\Label;
use Budgetlens\BolRetailerApi\Resources\Order;
use Budgetlens\BolRetailerApi\Resources\ProcessStatus;
use Budgetlens\BolRetailerApi\Tests\TestCase;
use Illuminate\Support\Collection;

class ShippingTest extends TestCase
{
    /** @test */
    public function getDeliveryOptions()
    {
        $this->useMock('200-delivery-options.json');

        $order = new Order([
            'orderItems' => [
                [
                    'orderItemId' => 2095052647,
                    'cancellationRequest' => false,
                    'fulfilment' => [
                        'method' => 'FBR'
                    ]
                ],
                [
                    'orderItemId' => 2095052648,
                    'cancellationRequest' => false,
                    'fulfilment' => [
                        'method' => 'FBB'
                    ]
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
        $this->assertInstanceOf(HandoverDetails::class, $options->first()->handoverDetails);
        $this->assertInstanceOf(\DateTime::class, $options->first()->handoverDetails->latestHandoverDateTime);
        $this->assertInstanceOf(\DateTime::class, $options->first()->handoverDetails->earliestHandoverDateTime);
        $this->assertSame(true, $options->first()->handoverDetails->meetsCustomerExpectation);
    }

    /** @test */
    public function createShippingLabel()
    {
        $this->useMock('200-create-shipping-label.json');

        $order = new Order([
            'orderItems' => [
                [
                    'orderItemId' => 2095052647
                ]
            ]
        ]);
        $status = $this->client->shipping->createLabel($order, '8f956bfc-fabe-45b4-b0e1-1b52a0896b74');

        $this->assertInstanceOf(ProcessStatus::class, $status);
        $this->assertSame(1, $status->id);
        $this->assertSame('CREATE_SHIPPING_LABEL', $status->eventType);
        $this->assertSame('PENDING', $status->status);
    }

    /** @test */
    public function getLabel()
    {
        $id = 'c628ba4f-f31a-4fac-a6a0-062326d0dbbd';
        $label = $this->client->shipping->getLabel($id);

        $this->assertInstanceOf(Label::class, $label);
        $this->assertSame($id, $label->id);
        $this->assertNotNull($label->label);
    }
}
