<?php
namespace Budgetlens\BolRetailerApi\Resources\Promotions;

use Budgetlens\BolRetailerApi\Resources\BaseResource;
use Budgetlens\BolRetailerApi\Resources\Country;
use Illuminate\Support\Collection;

class RelevanceScore extends BaseResource
{
    public $countryCode;
    public $relevanceScore;
}
