<?php
namespace Budgetlens\BolRetailerApi\Resources;

use Cassandra\Date;

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
        if (!$value instanceof \DateTime) {
            $value = new \DateTime($value);
        }

        $this->startDateTime = $value;

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
        if (!$value instanceof \DateTime) {
            $value = new \DateTime($value);
        }

        $this->endDateTime = $value;

        return $this;
    }

    public function toArray(): array
    {
        return collect(parent::toArray())
            ->map(function ($item) {
                if ($item instanceof \DateTime) {
                    return $item->format('c');
                }
            })
            ->all();
    }
}
