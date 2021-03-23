<?php
namespace Budgetlens\BolRetailerApi\Resources\Invoice;

class InvoiceXML extends AbstractInvoice
{
    public $contents;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
        $this->type = 'xml';
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
