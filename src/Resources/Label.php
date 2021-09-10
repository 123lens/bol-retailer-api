<?php
namespace Budgetlens\BolRetailerApi\Resources;

class Label extends BaseResource
{
    public $id;
    public $label;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }
}
