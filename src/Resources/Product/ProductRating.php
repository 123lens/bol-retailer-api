<?php

namespace Budgetlens\BolRetailerApi\Resources\Product;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class ProductRating extends BaseResource
{
    public ?int $rating;
    public ?int $count;
}
