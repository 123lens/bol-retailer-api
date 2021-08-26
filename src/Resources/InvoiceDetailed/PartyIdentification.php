<?php
namespace Budgetlens\BolRetailerApi\Resources\InvoiceDetailed;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class PartyIdentification extends BaseResource
{
    public $schemeID;
    public $value;


    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }
}
