<?php
namespace Budgetlens\BolRetailerApi\Resources;

class Inventory extends BaseResource
{
    public $ean;
    public $bsku;
    public $gradedStock;
    public $regularStock;
    public $title;

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
        $this->gradedStock = 0;
        $this->regularStock = 0;

        return $this;
    }
}
