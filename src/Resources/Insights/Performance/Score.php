<?php
namespace Budgetlens\BolRetailerApi\Resources\Insights\Performance;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class Score extends BaseResource
{
    public $conforms;
    public $numerator;
    public $denominator;
    public $value;
    public $distanceToNorm;

    public function setConformsAttribute($value): self
    {
        $this->conforms = (bool) $value;

        return $this;
    }
}
