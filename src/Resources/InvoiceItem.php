<?php
namespace Budgetlens\BolRetailerApi\Resources;

class InvoiceItem extends BaseResource
{
    public $invoiceId;
    public $invoiceMediaTypes;
    public $invoiceType;
    public $issueDate;
    public $invoicePeriod;
    public $legalMonetaryTotal;
    public $specificationMediaTypes;

    public function __construct($attributes = [])
    {
        $this->setDefaults();
        parent::__construct($attributes);
    }

    /**
     * Set Defaults
     * @return $this
     */
    public function setDefaults(): self
    {
        $this->invoiceMediaTypes = [];
        $this->specificationMediaTypes = [];

        return $this;
    }
    /**
     * Set Media Types
     * @param $value
     * @return $this
     */
    public function setInvoiceMediaTypesAttribute($value): self
    {
        $this->invoiceMediaTypes = collect($value->availableMediaTypes)->all();

        return $this;
    }

    /**
     * Set Specification Media Types
     * @param $value
     * @return $this
     */
    public function setSpecificationMediaTypesAttribute($value): self
    {
        $this->specificationMediaTypes = collect($value->availableMediaTypes)->all();

        return $this;
    }

    /**
     * Set Issue Date Attribute
     * @param $value
     * @return $this
     */
    public function setIssueDateAttribute($value): self
    {
        $this->issueDate = new \DateTime("@{$value}");

        return $this;
    }

    /**
     * Set Invoice Period Attribute
     * @param $value
     * @return $this
     */
    public function setInvoicePeriodAttribute($value): self
    {
        $this->invoicePeriod = collect($value)
            ->map(function ($value) {
                if (strlen($value) > 10) {
                    // using milliseconds.
                    $value /= 1000;
                }
                return new \DateTime("@{$value}");
            })->all();

        return $this;
    }

}
