<?php

namespace Budgetlens\BolRetailerApi\Resources;

use Budgetlens\BolRetailerApi\Resources\Product\PlacementCategory;
use Illuminate\Support\Collection;

class ProductPlacement extends BaseResource
{
    public null | string $url;
    public null | Collection $categories;

    public function setCategoriesAttribute($value): self
    {
        $items = [];

        foreach ($value as $item) {
            $items[] = new PlacementCategory($item);
        }

        $this->categories = collect($items);

        return $this;
    }
}
