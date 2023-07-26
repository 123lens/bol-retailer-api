<?php
namespace Budgetlens\BolRetailerApi\Resources;

class Product extends BaseResource
{
    public $ean;
    public $eans;
    public $title;
    public $announcedQuantity;

    public function setEansAttribute($value): self
    {
        $items = [];

        foreach ($value as $item) {
            $items[] = $item->ean;
        }

        $this->eans = collect($items);

        return $this;
    }

    public function toArray(): array
    {
        return collect(parent::toArray())
            ->reject(function ($value) {
                return empty($value);
            })
            ->all();
    }
}
