<?php
namespace Budgetlens\BolRetailerApi\Resources;

class Inbound extends BaseResource
{
    public $inboundId;
    public $reference;
    public $creationDateTime;
    public $state;
    public $labellingService;
    public $announcedBSKUs;
    public $announcedQuantity;
    public $receivedBSKUs;
    public $receivedQuantity;
    public $timeSlot;
    public $inboundTransporter;

    public function __construct($attributes = [])
    {
        $this->setDefaults();

        parent::__construct($attributes);
    }

    /**
     * Set Creation DateTime
     * @param $value
     * @return $this
     * @throws \Exception
     */
    public function setCreationDateTimeAttribute($value): self
    {
        $this->creationDateTime = new \DateTime($value);

        return $this;
    }
    /**
     * Set Timeslot
     * @param $value
     * @return $this
     * @throws \Exception
     */
    public function setTimeSlotAttribute($value): self
    {
        $this->timeSlot = new Timeslot($value);

        return $this;
    }

    /**
     * Set Inbound Transporter
     * @param $value
     * @return $this
     */
    public function setInboundTransporterAttribute($value): self
    {
        $this->inboundTransporter = new Transporter($value);

        return $this;
    }

    /**
     * Set Defaults
     * @return $this
     */
    public function setDefaults(): self
    {
        $this->labellingService = false;
        $this->announcedBSKUs = 0;
        $this->announcedQuantity = 0;
        $this->receivedBSKUs = 0;
        $this->receivedQuantity = 0;

        return $this;
    }
}
