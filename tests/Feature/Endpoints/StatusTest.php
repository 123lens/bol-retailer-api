<?php
namespace Budgetlens\BolRetailerApi\Tests\Feature\Endpoints;

use Budgetlens\BolRetailerApi\Exceptions\BolRetailerException;
use Budgetlens\BolRetailerApi\Exceptions\ValidationException;
use Budgetlens\BolRetailerApi\Resources\Address;
use Budgetlens\BolRetailerApi\Resources\Fulfilment;
use Budgetlens\BolRetailerApi\Resources\Offer;
use Budgetlens\BolRetailerApi\Resources\Order;
use Budgetlens\BolRetailerApi\Resources\ProcessStatus;
use Budgetlens\BolRetailerApi\Resources\ProcessStatusCollection;
use Budgetlens\BolRetailerApi\Resources\Product;
use Budgetlens\BolRetailerApi\Tests\TestCase;

class StatusTest extends TestCase
{
    /** @test */
    public function getStatusById()
    {
//        $this->useMock('200-orders.json');

        $status = $this->client->status->get(1);
        $this->assertInstanceOf(ProcessStatus::class, $status);
        $this->assertSame(1, $status->id);
        $this->assertSame(555551, $status->entityId);
        $this->assertSame('CONFIRM_SHIPMENT', $status->eventType);
        $this->assertSame('PENDING', $status->status);
    }

    /** @test */
    public function getStatusByResource()
    {
        $processStatus = new ProcessStatus([
            'id' => 1
        ]);

        $status = $this->client->status->get($processStatus);
        $this->assertInstanceOf(ProcessStatus::class, $status);
        $this->assertSame(1, $status->id);
        $this->assertSame(555551, $status->entityId);
        $this->assertSame('CONFIRM_SHIPMENT', $status->eventType);
        $this->assertSame('PENDING', $status->status);
    }

    /** @test */
    public function getStatusByEntityId()
    {
        $status = $this->client->status->getByEntityId(555551, 'CONFIRM_SHIPMENT');
        $this->assertInstanceOf(ProcessStatusCollection::class, $status);
        $this->assertSame(1, $status->processStatuses->first()->id);
        $this->assertSame(555551, $status->processStatuses->first()->entityId);
        $this->assertSame('CONFIRM_SHIPMENT', $status->processStatuses->first()->eventType);
        $this->assertSame('PENDING', $status->processStatuses->first()->status);
    }

    /** @test */
    public function getStatusByInvalidEventTypeThrowsAnException()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Validation Failed, See violations');

        try {
            $this->client->status->getByEntityId(555551, 'INVALID_EVENT_TYPE');
        } catch (ValidationException $e) {
            $violations = $e->getViolations();
            $this->assertCount(1, $violations);
            $this->assertSame('process-status-event-type', $violations->first()->name);
            throw $e;
        }
    }

    /** @test */
    public function getMultipleProcessStatusUsingBatch()
    {
        $batch = [1,2];

        $status = $this->client->status->batch($batch);
        $this->assertInstanceOf(ProcessStatusCollection::class, $status);
        $this->assertCount(2, $status->processStatuses);
        $this->assertSame(1, $status->processStatuses->first()->id);
        $this->assertSame(555551, $status->processStatuses->first()->entityId);
        $this->assertSame('CONFIRM_SHIPMENT', $status->processStatuses->first()->eventType);
        $this->assertSame('PENDING', $status->processStatuses->first()->status);

        $this->assertSame(2, $status->processStatuses->last()->id);
        $this->assertSame(555552, $status->processStatuses->last()->entityId);
        $this->assertSame('CANCEL_ORDER', $status->processStatuses->last()->eventType);
        $this->assertSame('SUCCESS', $status->processStatuses->last()->status);
    }

    /** @test */
    public function getMultipleProcessStatusUsingBatchWithCrazyMixup()
    {
        $batch = [1,new ProcessStatus(['id' => 2])];

        $status = $this->client->status->batch($batch);
        $this->assertInstanceOf(ProcessStatusCollection::class, $status);
        $this->assertCount(2, $status->processStatuses);
        $this->assertSame(1, $status->processStatuses->first()->id);
        $this->assertSame(555551, $status->processStatuses->first()->entityId);
        $this->assertSame('CONFIRM_SHIPMENT', $status->processStatuses->first()->eventType);
        $this->assertSame('PENDING', $status->processStatuses->first()->status);

        $this->assertSame(2, $status->processStatuses->last()->id);
        $this->assertSame(555552, $status->processStatuses->last()->entityId);
        $this->assertSame('CANCEL_ORDER', $status->processStatuses->last()->eventType);
        $this->assertSame('SUCCESS', $status->processStatuses->last()->status);
    }

    /** @test */
    public function getStatusByProcessWithInvalidDataThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Unable to collect status, 'id' or 'entityId' & 'eventType' are required");
        $status = new ProcessStatus([
            'eventType' => 'test'
        ]);
        $status = $this->client->status->get($status);
    }
}
