<?php
namespace Budgetlens\BolRetailerApi\Endpoints;

use Budgetlens\BolRetailerApi\Resources\ProcessStatus;
use Budgetlens\BolRetailerApi\Resources\Subscription;
use Budgetlens\BolRetailerApi\Resources\Subscriptions\SignatureKey;
use Illuminate\Support\Collection;

class Subscriptions extends BaseEndpoint
{
    /**
     * List Subscriptions
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
     * Get Subscription By ID
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
     * Create subscription
     * @param array $resources
     * @param string $url
     * @return ProcessStatus
     */
    public function create(array $resources, string $url): ProcessStatus
    {
        $subscription = new Subscription([
            'resources' => $resources,
            'url' => $url
        ]);

        $response = $this->performApiCall(
            'POST',
            "subscriptions",
            $subscription->toJson()
        );

        return new ProcessStatus(collect($response));
    }

    /**
     * Update subscription
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

    /**
     * Test Push Notification
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
     * Get Signature public keys
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
}
