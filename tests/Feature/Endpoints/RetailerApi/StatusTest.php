<?php
namespace Budgetlens\BolRetailerApi\Tests\Feature\Endpoints\RetailerApi;

use Budgetlens\BolRetailerApi\Exceptions\BolRetailerException;
use Budgetlens\BolRetailerApi\Exceptions\ProcessStillPendingException;
use Budgetlens\BolRetailerApi\Exceptions\ValidationException;
use Budgetlens\BolRetailerApi\Resources\ProcessStatus;
use Budgetlens\BolRetailerApi\Resources\ProcessStatusCollection;
use Budgetlens\BolRetailerApi\Tests\TestCase;

class StatusTest extends TestCase
{
    /** @test */
    public function pendingStateWillEndInException()
    {
        $this->expectException(ProcessStillPendingException::class);
        $this->client->status->waitUntilComplete(1, 2, 1);
    }

    /** @test */
    public function waitForStatusToComplete()
    {
        $this->useMock('200-status-success-response.json');

        $status = $this->client->status->waitUntilComplete(2, 2, 3);
        $this->assertInstanceOf(ProcessStatus::class, $status);
        $this->assertSame(2, $status->processStatusId);
        $this->assertSame('555552', $status->entityId);
        $this->assertSame('CANCEL_ORDER', $status->eventType);
        $this->assertSame('SUCCESS', $status->status);
    }

    /** @test */
    public function getStatusById()
    {
        $this->useMock('200-status-get-status-by-id.json');

        $status = $this->client->status->get(1);
        $this->assertInstanceOf(ProcessStatus::class, $status);
        $this->assertSame(1, $status->processStatusId);
        $this->assertSame('555551', $status->entityId);
        $this->assertSame('CONFIRM_SHIPMENT', $status->eventType);
        $this->assertSame('PENDING', $status->status);
    }

    /** @test */
    public function statusNotFoundException()
    {
        $this->useMock('404-status-not-found.json', 404);

        $this->expectException(BolRetailerException::class);
        $this->expectExceptionMessage('Error executing API call : Not Found : Not Found (404)');
        $status = $this->client->status->get(99999);
    }

    /** @test */
    public function getStatusByResource()
    {
        $this->useMock('200-status-get-status-by-resource.json');

        $processStatus = new ProcessStatus([
            'id' => 1
        ]);

        $status = $this->client->status->get($processStatus);
        $this->assertInstanceOf(ProcessStatus::class, $status);
        $this->assertSame(1, $status->processStatusId);
        $this->assertSame('555551', $status->entityId);
        $this->assertSame('CONFIRM_SHIPMENT', $status->eventType);
        $this->assertSame('PENDING', $status->status);
    }

    /** @test */
    public function getStatusByEntityId()
    {
        $this->useMock('200-status-get-status-by-entity-id.json');

        $status = $this->client->status->getByEntityId(555551, 'CONFIRM_SHIPMENT');
        $this->assertInstanceOf(ProcessStatusCollection::class, $status);
        $this->assertSame(1, $status->processStatuses->first()->processStatusId);
        $this->assertSame('555551', $status->processStatuses->first()->entityId);
        $this->assertSame('CONFIRM_SHIPMENT', $status->processStatuses->first()->eventType);
        $this->assertSame('PENDING', $status->processStatuses->first()->status);
    }

    /** @test */
    public function getStatusByInvalidEventTypeThrowsAnException()
    {
        $this->useMock('400-status-invalid-event-type.json', 400);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Validation Failed, See violations');

        try {
            $this->client->status->getByEntityId('555551', 'INVALID_EVENT_TYPE');
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
        $this->useMock('200-status-multiple-processes.json');

        $batch = [1,2];

        $status = $this->client->status->batch($batch);
        $this->assertInstanceOf(ProcessStatusCollection::class, $status);
        $this->assertCount(2, $status->processStatuses);
        $this->assertSame(1, $status->processStatuses->first()->processStatusId);
        $this->assertSame('555551', $status->processStatuses->first()->entityId);
        $this->assertSame('CONFIRM_SHIPMENT', $status->processStatuses->first()->eventType);
        $this->assertSame('PENDING', $status->processStatuses->first()->status);

        $this->assertSame(2, $status->processStatuses->last()->processStatusId);
        $this->assertSame('555552', $status->processStatuses->last()->entityId);
        $this->assertSame('CANCEL_ORDER', $status->processStatuses->last()->eventType);
        $this->assertSame('SUCCESS', $status->processStatuses->last()->status);
    }

    /** @test */
    public function getMultipleProcessStatusUsingBatchWithCrazyMixup()
    {
        $this->useMock('200-status-multiple-processes.json');

        $batch = [1,new ProcessStatus(['id' => 2])];
        $status = $this->client->status->batch($batch);
        $this->assertInstanceOf(ProcessStatusCollection::class, $status);
        $this->assertCount(2, $status->processStatuses);
        $this->assertSame(1, $status->processStatuses->first()->processStatusId);
        $this->assertSame('555551', $status->processStatuses->first()->entityId);
        $this->assertSame('CONFIRM_SHIPMENT', $status->processStatuses->first()->eventType);
        $this->assertSame('PENDING', $status->processStatuses->first()->status);

        $this->assertSame(2, $status->processStatuses->last()->processStatusId);
        $this->assertSame('555552', $status->processStatuses->last()->entityId);
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
