<?php
namespace Budgetlens\BolRetailerApi\Resources;

use Budgetlens\BolRetailerApi\Resources\ProcessStatus\Link;
use \Budgetlens\BolRetailerApi\Types\ProcessStatus as ProcessStatusType;
use Illuminate\Support\Collection;

class ProcessStatus extends BaseResource
{
    public $processStatusId;
    public $entityId;
    public $eventType;
    public $description;
    public $status;
    public $errorMessage;
    public $createTimestamp;
    public $links;

    public function __construct($attributes = [])
    {
        $this->setDefaults();

        parent::__construct($attributes);
    }

    public function setCreateTimestampAttribute($value): self
    {
        if (!$value instanceof \DateTime) {
            $value = new \DateTime($value);
        }

        $this->createTimestamp = $value;

        return $this;
    }

    /**
     * Set ID Attribute
     * @param $value
     * @return $this
     */
    public function setIdAttribute($value): self
    {
        $this->id = (int)$value;

        return $this;
    }

    public function setLinksAttribute($value): self
    {
        $items = new Collection();

        collect($value)->each(function ($item) use ($items) {
            if (!$item instanceof Link) {
                $item = new Link($item);
            }
            $items->push($item);
        });

        $this->links = $items;

        return $this;
    }

    /**
     * Set Defaults
     *
     * @return $this
     */
    public function setDefaults(): self
    {
        $this->status = ProcessStatusType::DEFAULT;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->status === ProcessStatusType::PENDING;
    }

    /**
     * @return bool
     */
    public function isFailure(): bool
    {
        return $this->status === ProcessStatusType::FAILURE;
    }

    /**
     * @return bool
     */
    public function isTimeout(): bool
    {
        return $this->status === ProcessStatusType::TIMEOUT;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->status === ProcessStatusType::SUCCESS;
    }
}
