<?php

namespace Budgetlens\BolRetailerApi\Resources\Filters;

use Budgetlens\BolRetailerApi\Resources\BaseResource;
use Illuminate\Support\Collection;

class Category extends BaseResource
{
    public null | string $categoryName;
    public null | Collection $categoryValues;

    public function setCategoryValuesAttribute($value): self
    {
        $items = [];
        foreach ($value as $item) {
            $items[] = new CategoryValue($item);
        }

        $this->categoryValues = collect($items);

        return $this;
    }
}
