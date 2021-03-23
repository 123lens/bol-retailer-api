<?php
namespace Budgetlens\BolRetailerApi\Resources\Invoice;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

abstract class AbstractInvoice extends BaseResource
{
    public $type;
    public $id;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }
}
