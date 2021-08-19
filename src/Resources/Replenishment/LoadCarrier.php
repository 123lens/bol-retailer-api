<?php
namespace Budgetlens\BolRetailerApi\Resources\Replenishment;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class LoadCarrier extends BaseResource
{
    public $sscc;
    public $transportState;
    public $transportStateUpdateDateTime;

    public function setTransportStateUpdateDateTimeAttribute($value): self
    {
        if (!$value instanceof \DateTime) {
            $value = new \DateTime($value);
        }

        $this->transportStateUpdateDateTime = $value;

        return $this;
    }
}
