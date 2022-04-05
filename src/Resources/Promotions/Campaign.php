<?php
namespace Budgetlens\BolRetailerApi\Resources\Promotions;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class Campaign extends BaseResource
{
    public $name;
    public $startDateTime;
    public $endDateTime;

    public function setStartDateTime($value): self
    {
        if (!$value instanceof \DateTime) {
            $value = new \DateTime($value);
        }

        $this->startDateTime = $value;

        return $this;
    }

    public function setEndDateTime($value): self
    {
        if (!$value instanceof \DateTime) {
            $value = new \DateTime($value);
        }

        $this->endDateTime = $value;

        return $this;
    }
}
