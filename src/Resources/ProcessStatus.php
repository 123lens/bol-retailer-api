<?php
namespace Budgetlens\BolRetailerApi\Resources;

use \Budgetlens\BolRetailerApi\Types\ProcessStatus as ProcessStatusType;

class ProcessStatus extends BaseResource
{
    public $id;
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

    /**
     * Set Defaults
     *
     * @return $this
     */
    public function setDefaults(): self
    {
        $this->status = \Budgetlens\BolRetailerApi\Types\ProcessStatus::DEFAULT;

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
