<?php
namespace Budgetlens\BolRetailerApi\Resources;

use Budgetlens\BolRetailerApi\Resources\Replenishment\Warehouse;

class ProductDestination extends BaseResource
{
    public $destinationWarehouse;
    public $eans;

    public function setDestinationWarehouseAttribute($value): self
    {
        if (!$value instanceof Warehouse) {
            $value = new Warehouse($value->address);
        }

        $this->destinationWarehouse = $value;

        return $this;
    }

    public function setEansAttribute($value): self
    {
        $items = [];

        foreach ($value as $item) {
            $items[] = $item->ean;
        }

        $this->eans = collect($items);

        return $this;
    }
//    public function toArray(): array
//    {
//        return collect(parent::toArray())
//            ->reject(function ($value) {
//                return empty($value);
//            })
//            ->all();
//    }
}
