<?php

namespace Budgetlens\BolRetailerApi\Tests\Feature\Endpoints\RetailerApi;

use Budgetlens\BolRetailerApi\Resources\ProcessStatus;
use Budgetlens\BolRetailerApi\Resources\Subscription;
use Budgetlens\BolRetailerApi\Resources\Subscriptions\SignatureKey;
use Budgetlens\BolRetailerApi\Tests\TestCase;
use Budgetlens\BolRetailerApi\Types\SubscriptionResource;
use Budgetlens\BolRetailerApi\Types\SubscriptionType;
use Illuminate\Support\Collection;

class SubscriptionsTest extends TestCase
{
    /** @test */
    public function listSubscriptions()
    {
        $this->useMock('200-list-subscriptions.json');

        $subscriptions = $this->client->subscriptions->list();
        $this->assertInstanceOf(Collection::class, $subscriptions);
        $this->assertCount(1, $subscriptions);
        $this->assertInstanceOf(Subscription::class, $subscriptions->first());
        $this->assertSame('1234', $subscriptions->first()->id);
        $this->assertInstanceOf(Collection::class, $subscriptions->first()->resources);
        $this->assertSame('PROCESS_STATUS', $subscriptions->first()->resources->first());
        $this->assertSame('https://www.example.com/push', $subscriptions->first()->url);
    }

    /** @test */
    public function getSubscriptionById()
    {
        $this->useMock('200-get-subscription-by-id.json');

        $id = '1234';
        $subscription = $this->client->subscriptions->get($id);

        $this->assertInstanceOf(Subscription::class, $subscription);
        $this->assertSame('1234', $subscription->id);
        $this->assertInstanceOf(Collection::class, $subscription->resources);
        $this->assertSame('PROCESS_STATUS', $subscription->resources->first());
        $this->assertSame('https://www.example.com/push', $subscription->url);
    }

    /** @test */
    public function createSubscription()
    {
        $this->useMock('200-create-subscription.json');
        $resources = [SubscriptionResource::PROCESS_STATUS];
        $url = 'https://www.example.com/push';
        $status = $this->client->subscriptions->create(
            $resources,
            $url,
            SubscriptionType::WEBHOOK,
            false
        );
        $this->assertInstanceOf(ProcessStatus::class, $status);
        $this->assertSame(1, $status->processStatusId);
        $this->assertSame('CREATE_SUBSCRIPTION', $status->eventType);
        $this->assertSame('PENDING', $status->status);
        $this->assertInstanceOf(\DateTime::class, $status->createTimestamp);
    }

    /** @test */
    public function updateSubscription()
    {
        $this->useMock('200-update-subscription.json');

        $id = '1234';
        $resources = [SubscriptionResource::PROCESS_STATUS];
        $url = 'https://www.example.com/push';

        $status = $this->client->subscriptions->update(
            $id,
            $resources,
            $url,
            SubscriptionType::WEBHOOK,
            false
        );

        $this->assertInstanceOf(ProcessStatus::class, $status);
        $this->assertSame(1, $status->processStatusId);
        $this->assertSame('UPDATE_SUBSCRIPTION', $status->eventType);
        $this->assertSame('PENDING', $status->status);
        $this->assertInstanceOf(\DateTime::class, $status->createTimestamp);
    }

    /** @test */
    public function deleteSubscription()
    {
        $this->useMock('200-delete-subscription.json');

        $id = '1234';

        $status = $this->client->subscriptions->delete($id);

        $this->assertInstanceOf(ProcessStatus::class, $status);
        $this->assertSame(1, $status->processStatusId);
        $this->assertSame('DELETE_SUBSCRIPTION', $status->eventType);
        $this->assertSame('PENDING', $status->status);
        $this->assertInstanceOf(\DateTime::class, $status->createTimestamp);
    }

    /** @test */
    public function sendTestNotification()
    {
        $this->useMock('200-send-test-subscription.json');

        $id = '54321';

        $status = $this->client->subscriptions->test($id);

        $this->assertInstanceOf(ProcessStatus::class, $status);
        $this->assertSame(1, $status->processStatusId);
        $this->assertSame('SEND_SUBSCRIPTION_TST_MSG', $status->eventType);
        $this->assertSame('PENDING', $status->status);
        $this->assertInstanceOf(\DateTime::class, $status->createTimestamp);
    }

    /** @test */
    public function getSignatureKeys()
    {
        $keys = $this->client->subscriptions->getSignatureKeys();
        $this->assertInstanceOf(Collection::class, $keys);
        $this->assertCount(1, $keys);
        $this->assertInstanceOf(SignatureKey::class, $keys->first());
        $this->assertSame('0', $keys->first()->id);
        $this->assertSame('RSA', $keys->first()->type);
        $this->assertNotNull($keys->first()->publicKey);
    }
}
