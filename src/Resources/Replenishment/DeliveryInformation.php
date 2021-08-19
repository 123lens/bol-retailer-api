<?php
namespace Budgetlens\BolRetailerApi\Resources\Replenishment;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class DeliveryInformation extends BaseResource
{
    public $expectedDeliveryDate;
    public $transporterCode;
    public $destinationWarehouse;

    public function setExpectedDeliveryDateAttribute($value): self
    {
        if (!$value instanceof \DateTime) {
            $value = new \DateTime($value);
        }

        $this->expectedDeliveryDate = $value;

        return $this;
    }

    public function setDestinationWarehouseAttribute($value): self
    {
        if (!$value instanceof Warehouse) {
            $value = new Warehouse($value);
        }

        $this->destinationWarehouse = $value;

        return $this;
    }


    public function toArray(): array
    {
        return collect(parent::toArray())
            ->map(function ($item) {
                if ($item instanceof \DateTime) {
                    return $item->format('Y-m-d');
                }

                return $item;
            })
            ->all();
    }
}
