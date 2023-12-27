<?php

namespace Budgetlens\BolRetailerApi\Endpoints;

use Budgetlens\BolRetailerApi\Resources\ProcessStatus;
use Budgetlens\BolRetailerApi\Resources\Subscription;
use Budgetlens\BolRetailerApi\Resources\Subscriptions\SignatureKey;
use Budgetlens\BolRetailerApi\Types\SubscriptionType;
use Illuminate\Support\Collection;

class Subscriptions extends BaseEndpoint
{
    /**
     * List Subscriptions
     * @see https://api.bol.com/retailer/public/redoc/v10/retailer.html#operation/get-push-notification-subscriptions
     * @return Collection
     */
    public function list(): Collection
    {
        $response = $this->performApiCall(
            'GET',
            "subscriptions"
        );

        $collection = new Collection();

        collect($response->subscriptions)->each(function ($item) use ($collection) {
            $collection->push(new Subscription($item));
        });

        return $collection;
    }

    /**
     * Create subscription
     * @see https://api.bol.com/retailer/public/redoc/v10/retailer.html#operation/post-push-notification-subscription
     * @param array $resources
     * @param string $url
     * @param SubscriptionType $subscriptionType
     * @param bool $enabled
     * @return ProcessStatus
     */
    public function create(
        array $resources,
        string $url,
        SubscriptionType $subscriptionType,
        bool $enabled = true
    ): ProcessStatus {
        $subscription = new Subscription([
            'resources' => $resources,
            'url' => $url,
            'subscriptionType' => $subscriptionType->value,
            'enabled' => $enabled,
        ]);

        $response = $this->performApiCall(
            'POST',
            "subscriptions",
            $subscription->toJson()
        );

        return new ProcessStatus(collect($response));
    }

    /**
     * Get Signature public keys
     * @see https://api.bol.com/retailer/public/redoc/v8/retailer.html#operation/get-subscription-keys
     * @return Collection
     */
    public function getSignatureKeys(): Collection
    {
        $response = $this->performApiCall(
            'GET',
            "subscriptions/signature-keys"
        );

        $collection = new Collection();

        collect($response->signatureKeys)->each(function ($item) use ($collection) {
            $collection->push(new SignatureKey($item));
        });

        return $collection;
    }

    /**
     * Test Push Notification
     * @see https://api.bol.com/retailer/public/redoc/v8/retailer.html#operation/post-test-push-notification
     * @param string $id
     * @return ProcessStatus
     */
    public function test(string $id): ProcessStatus
    {
        $response = $this->performApiCall(
            'POST',
            "subscriptions/test/{$id}"
        );

        return new ProcessStatus(collect($response));
    }

    /**
     * Get Subscription By ID
     * @see https://api.bol.com/retailer/public/redoc/v8/retailer.html#operation/get-push-notification-subscription
     * @param string $id
     * @return Subscription
     */
    public function get(string $id): Subscription
    {
        $response = $this->performApiCall(
            'GET',
            "subscriptions/{$id}"
        );

        return new Subscription(collect($response));
    }

    /**
     * Update subscription
     * @see https://api.bol.com/retailer/public/redoc/v8/retailer.html#operation/put-push-notification-subscription
     * @param string $id
     * @param array $resources
     * @param string $url
     * @return ProcessStatus
     */
    public function update(string $id, array $resources, string $url): ProcessStatus
    {
        $subscription = new Subscription([
            'resources' => $resources,
            'url' => $url
        ]);

        $response = $this->performApiCall(
            'PUT',
            "subscriptions/{$id}",
            $subscription->toJson()
        );

        return new ProcessStatus(collect($response));
    }

    /**
     * Delete subscription
     * @see https://api.bol.com/retailer/public/redoc/v8/retailer.html#operation/delete-push-notification-subscription
     * @param string $id
     * @return ProcessStatus
     */
    public function delete(string $id): ProcessStatus
    {
        $response = $this->performApiCall(
            'DELETE',
            "subscriptions/{$id}"
        );

        return new ProcessStatus(collect($response));
    }
}
