<?php
namespace Budgetlens\BolRetailerApi\Resources;

use Budgetlens\BolRetailerApi\Resources\InvoiceItem as InvoiceResource;
use Illuminate\Support\Collection;

class InvoicePDF extends BaseResource
{
    public $type = 'pdf';
    public $id;
    public $contents;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
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

    /**
     * Save PDF
     * @param $path
     * @return string|null
     */
    public function save($path): ?string
    {
        if (!@file_put_contents("{$path}/{$this->id}.pdf", $this->contents)) {
            return null;
        } else {
            return "{$path}/{$this->id}.pdf";
        }
    }
}
