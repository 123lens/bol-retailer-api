<?php
namespace Budgetlens\BolRetailerApi\Resources;

class PackageRestriction extends BaseResource
{
    public $maxWeight;
    public $maxDimensions;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }
}
