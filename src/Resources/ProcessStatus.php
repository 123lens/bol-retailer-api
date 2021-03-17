<?php
namespace Budgetlens\BolRetailerApi\Resources;

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
     * Set Defaults
     *
     * @return $this
     */
    public function setDefaults(): self
    {
        $this->status = \Budgetlens\BolRetailerApi\Types\ProcessStatus::DEFAULT;

        return $this;
    }
}