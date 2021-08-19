<?php
namespace Budgetlens\BolRetailerApi\Resources\Replenishment;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class StateTransition extends BaseResource
{
    public $state;
    public $stateDateTime;

    public function setStateDateTimeAttribute($value): self
    {
        if (!$value instanceof \DateTime) {
            $value = new \DateTime($value);
        }

        $this->stateDateTime = $value;

        return $this;
    }
}
