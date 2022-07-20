<?php
namespace Budgetlens\BolRetailerApi\Endpoints;

use Budgetlens\BolRetailerApi\Exceptions\ProcessStillPendingException;
use Budgetlens\BolRetailerApi\Exceptions\RateLimitException;
use Budgetlens\BolRetailerApi\Resources\ProcessStatus;
use Budgetlens\BolRetailerApi\Resources\ProcessStatusCollection;

class Status extends BaseEndpoint
{

    /**
     * Get process status and wait for it to finish
     * @param $status
     * @param int $maxRetries
     * @param int $timeOut
     * @return ProcessStatus
     * @throws ProcessStillPendingException
     */
    public function waitUntilComplete($status, int $maxRetries = 10, int $timeOut = 3): ProcessStatus
    {
        $processStatus = null;
        $pending = true;
        for ($i = 0; $i < $maxRetries && $pending; $i++) {
            try {
                $processStatus = $this->get($status);
                $pending = $processStatus->isPending();
                if ($pending) {
                    sleep($timeOut);
                }
            } catch (RateLimitException $e) {
                // hit a rate limit.use retry-after to wait.
                sleep($e->getRetryAfter());
            }
        }

        if ($pending) {
            // still pending
            throw new ProcessStillPendingException();
        } else {
            return $processStatus;
        }
    }

    /**
     * Get Process Status
     * @param $status - ProcessStatus | string
     * @return ProcessStatus|ProcessStatusCollection
     * @throws \InvalidArgumentException
     */
    public function get($status)
    {
        if ($status instanceof ProcessStatus) {
            if ($status->id) {
                return $this->getById((string)$status->id);
            }
            if ($status->entityId && $status->eventType) {
                return $this->getByEntityId((string)$status->entityId, $status->eventType);
            } else {
                throw new \InvalidArgumentException(
                    "Unable to collect status, 'id' or 'entityId' & 'eventType' are required"
                );
            }
        } else {
            return $this->getById((string)$status);
        }
    }

    /**
     * Get Process status by id
     * @see https://api.bol.com/retailer/public/Retailer-API/v6/functional/process-status.html
     * @param string $id
     * @return ProcessStatus
     */
    public function getById(string $id): ProcessStatus
    {
        $response = $this->performApiCall(
            'GET',
            "process-status/{$id}"
        );

        return new ProcessStatus(collect($response));
    }

    /**
     * Retrieve Process status by entitiyId & eventyType
     * @see https://api.bol.com/retailer/public/Retailer-API/v6/functional/process-status.html
     * @param string $id
     * @param string $eventType
     * @param int $page
     * @return ProcessStatusCollection
     */
    public function getByEntityId(string $id, string $eventType, int $page = 1): ProcessStatusCollection
    {
        $response = $this->performApiCall(
            'GET',
            'process-status' . $this->buildQueryString([
                'entity-id' => $id,
                'event-type' => $eventType,
                'page' => $page
            ])
        );
        return new ProcessStatusCollection(collect($response));
    }

    /**
     * Get process statusses for batch of processes
     * @param array $ids
     * @return ProcessStatusCollection
     */
    public function batch(array $ids): ProcessStatusCollection
    {
        // check if ids contains ProcessStatus resource.
        $items = collect($ids)->map(function ($item) {
            if ($item instanceof ProcessStatus) {
                return ['id' => $item->id];
            }
            return ['id' => $item];
        })->all();

        $response = $this->performApiCall(
            'POST',
            'process-status',
            json_encode([
                'processStatusQueries' => $items
            ])
        );

        return new ProcessStatusCollection(collect($response));
    }
}
