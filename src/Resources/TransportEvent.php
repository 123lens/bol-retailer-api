<?php
namespace Budgetlens\BolRetailerApi\Resources;

use Illuminate\Support\Collection;

class TransportEvent extends BaseResource
{
    public $eventCode;
    public $eventDateTime;

    public function setEventDateTime($value): self
    {
        if (!$value instanceof \DateTime) {
            $value = new \DateTime($value);
        }

        $this->eventDateTime = $value;

        return $this;
    }
}
