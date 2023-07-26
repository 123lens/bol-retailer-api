<?php

namespace Budgetlens\BolRetailerApi\Resources\Filters;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class Category extends BaseResource
{
    public $categoryName;
    public $categoryValues = [];

    public function setCategoryValuesAttribute($value): self
    {
        foreach ($value as $item) {
            $this->categoryValues[] = new CategoryValue($item);
        }

        return $this;
    }
}
