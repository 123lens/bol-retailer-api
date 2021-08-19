<?php
namespace Budgetlens\BolRetailerApi\Resources\Replenishment;

use Budgetlens\BolRetailerApi\Resources\BaseResource;
use Budgetlens\BolRetailerApi\Resources\Concerns\HasSaveable;

class Picklist extends BaseResource
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
