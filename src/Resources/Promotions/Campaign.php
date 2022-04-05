<?php
namespace Budgetlens\BolRetailerApi\Resources\Promotions;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class Campaign extends BaseResource
{
    public $name;
    public $startDateTime;
    public $endDateTime;

    public function setStartDateTimeAttribute($value): self
    {
        if (!$value instanceof \DateTime) {
            $value = new \DateTime($value);
        }

        $this->startDateTime = $value;

        return $this;
    }

    public function setEndDateTimeAttribute($value): self
    {
        if (!$value instanceof \DateTime) {
            $value = new \DateTime($value);
        }

        $this->endDateTime = $value;

        return $this;
    }
}
