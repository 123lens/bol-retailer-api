<?php
namespace Budgetlens\BolRetailerApi\Resources\Invoice;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class Period extends BaseResource
{
    public $start;
    public $end;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    public function setStartAttribute($value): self
    {
        if (!$value instanceof \DateTime) {
            $value = new \DateTime($value);
        }

        $this->start = $value;

        return $this;
    }

    public function setEndAttribute($value): self
    {
        if (!$value instanceof \DateTime) {
            $value = new \DateTime($value);
        }

        $this->end = $value;

        return $this;
    }
}
