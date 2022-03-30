<?php
namespace Budgetlens\BolRetailerApi\Resources\Replenishment;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class DeliveryDate extends BaseResource
{
    public $date;

    /**
     * Set Date
     * @param $value
     * @return $this
     * @throws \Exception
     */
    public function setDateAttribute($value): self
    {
        if (!$value instanceof \DateTime) {
            $value = new \DateTime($value);
        }

        $this->date = $value;

        return $this;
    }
}
