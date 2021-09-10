<?php
namespace Budgetlens\BolRetailerApi\Resources\Invoice;

use Budgetlens\BolRetailerApi\Resources\Concerns\HasSaveable;

class InvoicePDF extends AbstractInvoice
{
    use HasSaveable;

    public $contents;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
        $this->type = 'pdf';
    }

    /**
     * Set Contents
     * @param $value
     * @return $this
     */
    public function setContentsAttribute($value): self
    {
        $this->contents = $value;

        return $this;
    }
}
