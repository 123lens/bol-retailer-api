<?php
namespace Budgetlens\BolRetailerApi\Resources\Insights;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class Country extends BaseResource
{
    public $countryCode;
    public $value;
    public $minimum;
    public $maximum;
}
