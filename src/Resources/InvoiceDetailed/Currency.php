<?php
namespace Budgetlens\BolRetailerApi\Resources\InvoiceDetailed;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class Currency extends BaseResource
{
    public $currencyID;
    public $value;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }
}
