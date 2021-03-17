<?php
namespace Budgetlens\BolRetailerApi\Resources;

class Stock extends BaseResource
{
    public $amount;
    public $managedByRetailer;

    public function __construct($attributes = [])
    {
        $this->setDefaults();

        parent::__construct($attributes);
    }

    /**
     * Set Defaults
     * @return $this
     */
    public function setDefaults(): self
    {
        $this->amount = 0;
        $this->managedByRetailer = false;

        return $this;
    }
}
