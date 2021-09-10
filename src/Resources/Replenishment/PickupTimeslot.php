<?php
namespace Budgetlens\BolRetailerApi\Resources\Replenishment;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class PickupTimeslot extends BaseResource
{
    public $fromDateTime;
    public $untilDateTime;

    /**
     * Set From DateTime
     * @param $value
     * @return $this
     * @throws \Exception
     */
    public function setFromDateTimeAttribute($value): self
    {
        if (!$value instanceof \DateTime) {
            $value = new \DateTime($value);
        }

        $this->fromDateTime = $value;

        return $this;
    }

    /**
     * Set Until DateTime
     * @param $value
     * @return $this
     * @throws \Exception
     */
    public function setUntilDateTimeAttribute($value): self
    {
        if (!$value instanceof \DateTime) {
            $value = new \DateTime($value);
        }

        $this->untilDateTime = $value;

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
