<?php
namespace Budgetlens\BolRetailerApi\Resources;

class Product extends BaseResource
{
    public $ean;
    public $title;
    public $announcedQuantity;

    public function toArray(): array
    {
        return collect(parent::toArray())
            ->reject(function ($value) {
                return empty($value);
            })
            ->all();
    }
}
