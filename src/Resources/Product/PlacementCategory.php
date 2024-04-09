<?php

namespace Budgetlens\BolRetailerApi\Resources\Product;

use Budgetlens\BolRetailerApi\Resources\BaseResource;
use Illuminate\Support\Collection;

class PlacementCategory extends BaseResource
{
    public ?string $categoryId;
    public ?string $categoryName;
    public ?Collection $subcategories;

    public function setSubcategoriesAttribute($value)
    {
        $items = [];

        foreach ($value as $item) {
            $items[] = new PlacementSubCategory($item);
        }

        $this->subcategories = collect($items);

        return $this;
    }
}
