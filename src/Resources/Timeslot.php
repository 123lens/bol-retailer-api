<?php
namespace Budgetlens\BolRetailerApi\Resources;

class Timeslot extends BaseResource
{
    public $startDateTime;
    public $endDateTime;

    /**
     * Set StartDateTime
     * @param $value
     * @return $this
     * @throws \Exception
     */
    public function setStartDateTimeAttribute($value): self
    {
        $this->startDateTime = new \DateTime($value);

        return $this;
    }

    /**
     * Set EndDateTime
     * @param $value
     * @return $this
     * @throws \Exception
     */
    public function setEndDateTimeAttribute($value): self
    {
        $this->endDateTime = new \DateTime($value);

        return $this;
    }
}
