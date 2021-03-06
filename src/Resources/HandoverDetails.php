<?php
namespace Budgetlens\BolRetailerApi\Resources;

class HandoverDetails extends BaseResource
{
    public $meetsCustomerExpectation;
    public $latestHandoverDateTime;
    public $collectionMethod;

    public function __construct($attributes = [])
    {
        $this->setDefaults();

        parent::__construct($attributes);
    }

    /**
     * Set Defaults
     * @return $this
     */
    public function setDefaults(): self
    {
        $this->meetsCustomerExpectation = false;

        return $this;
    }

    /**
     * Set Latest Handover Date Time Attribute
     * @param $value
     * @return $this
     * @throws \Exception
     */
    public function setLatestHandoverDateTimeAttribute($value): self
    {
        $this->latestHandoverDateTime = new \DateTime($value);

        return $this;
    }
}
