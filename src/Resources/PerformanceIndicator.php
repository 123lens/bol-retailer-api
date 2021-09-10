<?php
namespace Budgetlens\BolRetailerApi\Resources;

use Budgetlens\BolRetailerApi\Resources\Insights\Country;
use Budgetlens\BolRetailerApi\Resources\Insights\PerformanceDetail;
use Budgetlens\BolRetailerApi\Resources\Insights\Period;
use Illuminate\Support\Collection;

class PerformanceIndicator extends BaseResource
{
    public $name;
    public $type;
    public $details;

    public function __construct($attributes = [])
    {
        $this->details = [];

        parent::__construct($attributes);
    }

    public function setDetailsAttribute($value)
    {
        $this->details = new PerformanceDetail($value);

        return $this;
    }

    public function toArray(): array
    {
        return collect(parent::toArray())
            ->when(!is_null($this->details), function ($collection) {
                return $collection->put('details', $this->details->toArray());
            })
            ->reject(function ($value) {
                return is_null($value);
            })
            ->all();
    }
}
