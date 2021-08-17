<?php
namespace Budgetlens\BolRetailerApi\Resources;

use Illuminate\Support\Collection;

class Reduction extends BaseResource
{
    public $maximumPrice;
    public $costReduction;
    public $startDate;
    public $endDate;

    /**
     * Set Start Date
     * @param $value
     * @return $this
     * @throws \Exception
     */
    public function setStartDateAttribute($value)
    {
        $this->startDate = new \DateTime($value);

        return $this;
    }

    /**
     * Set End Date
     * @param $value
     * @return $this
     * @throws \Exception
     */
    public function setEndDateAttribute($value)
    {
        $this->endDate = new \DateTime($value);

        return $this;
    }
}
