<?php
namespace Budgetlens\BolRetailerApi\Resources\Product;

use Budgetlens\BolRetailerApi\Resources\BaseResource;
use Budgetlens\BolRetailerApi\Resources\Product\Assets\Variant;
use Budgetlens\BolRetailerApi\Resources\Reduction;
use Illuminate\Support\Collection;

class Asset extends BaseResource
{
    public null | string $usage;
    public null | int $order;
    public null | Collection $variants;

    public function setVariantsAttribute($value)
    {
        $items = [];

        foreach ($value as $item) {
            $items[] = new Variant($item);
        }

        $this->variants = collect($items);

        return $this;
    }
}
