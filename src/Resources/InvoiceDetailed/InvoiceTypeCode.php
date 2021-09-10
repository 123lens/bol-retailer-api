<?php
namespace Budgetlens\BolRetailerApi\Resources\InvoiceDetailed;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class InvoiceTypeCode extends BaseResource
{
    public $listID;
    public $value;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }
}
