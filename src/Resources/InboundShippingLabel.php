<?php
namespace Budgetlens\BolRetailerApi\Resources;

use Budgetlens\BolRetailerApi\Resources\Concerns\HasSaveable;

class InboundShippingLabel extends BaseResource
{
    use HasSaveable;

    public $id;
    public $contents;
    protected $fileExt = 'pdf';

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }
}
