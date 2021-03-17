<?php
namespace Budgetlens\BolRetailerApi\Endpoints;

use Budgetlens\BolRetailerApi\Resources\ProcessStatus;
use Budgetlens\BolRetailerApi\Resources\ProcessStatusCollection;

class Status extends BaseEndpoint
{
    /**
     * Get Process Status
     * @param $status - ProcessStatus | int
     * @return ProcessStatus|ProcessStatusCollection
     * @throws \InvalidArgumentException
     */
    public function get($status)
    {
        if ($status instanceof ProcessStatus) {
            if ($status->id) {
                return $this->getById((int)$status->id);
            }
            if ($status->entityId && $status->eventType) {
                return $this->getByEntityId((int)$status->entityId, $status->eventType);
            } else {
                throw new \InvalidArgumentException(
                    "Unable to collect status, 'id' or 'entityId' & 'eventType' are required"
                );
            }
        } else {
            return $this->getById((int)$status);
        }
    }

    /**
     * Get Process status by id
     * @see https://api.bol.com/retailer/public/Retailer-API/v4/functional/process-status.html
     * @param int $id
     * @return ProcessStatus
     */
    public function getById(int $id): ProcessStatus
    {
        $response = $this->performApiCall(
            'GET',
            "process-status/{$id}"
        );

        return new ProcessStatus(collect($response));
    }

    /**
     * Retrieve Process status by entitiyId & eventyType
     * @see https://api.bol.com/retailer/public/Retailer-API/v4/functional/process-status.html
     * @param int $id
     * @param string $eventType
     * @param int $page
     * @return ProcessStatusCollection
     */
    public function getByEntityId(int $id, string $eventType, int $page = 1): ProcessStatusCollection
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
