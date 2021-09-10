<?php
namespace Budgetlens\BolRetailerApi\Resources;

class Store extends BaseResource
{
    public $productTitle;
    public $visible;

    public function __construct($attributes = [])
    {
        $this->visible = [];

        parent::__construct($attributes);
    }
}
