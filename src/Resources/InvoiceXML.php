<?php
namespace Budgetlens\BolRetailerApi\Resources;

use Budgetlens\BolRetailerApi\Resources\InvoiceItem as InvoiceResource;
use Illuminate\Support\Collection;

class InvoiceXML extends BaseResource
{
    public $type = 'xml';
    public $id;
    public $contents;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    /**
     * Set Contents Attribute
     * @param $value
     * @return $this
     */
    public function setContentsAttribute($value): self
    {
        $this->contents = new \SimpleXMLElement($value);

        return $this;
    }

    public function getXml()
    {
        return $this->contents->saveXML();
    }
}
