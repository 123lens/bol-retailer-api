<?php
namespace Budgetlens\BolRetailerApi\Resources\InvoiceDetailed;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class Quantity extends BaseResource
{
    public $unitCode;
    public $unitCodeListID;
    public $value;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }
}
