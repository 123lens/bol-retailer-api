<?php

namespace Budgetlens\BolRetailerApi\Requests\Products;

use Budgetlens\BolRetailerApi\Requests\BaseRequest;
use Budgetlens\BolRetailerApi\Resources\Concerns\HasAttributes;

class ListProductsRequest extends BaseRequest
{
    use HasAttributes;

    private null | string $searchTerm;
    private null | string $categoryId;
    private null | string $sort;
    private array $filterRanges = [];
    private array $filterValues = [];
    private string $countryCode = "NL";
    private int $page = 1;


}
