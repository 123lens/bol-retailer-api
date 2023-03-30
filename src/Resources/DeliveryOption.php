<?php
namespace Budgetlens\BolRetailerApi\Resources;

use Budgetlens\BolRetailerApi\Types\DeliveryCodes;

class DeliveryOption extends BaseResource
{
    public $shippingLabelOfferId;
    public $recommended;
    public $validUntilDate;
    public $transporterCode;
    public $labelType;
    public $labelDisplayName;
    public $labelPrice;
    public $packageRestrictions;
    public $handoverDetails;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    public function setValidUntilDateAttribute($value): self
    {
        if (!$value instanceof \DateTime) {
            $value = new \DateTime($value);
        }

        $this->validUntilDate = $value;

        return $this;
    }

    public function setRecommendedAttribute($value): self
    {
        $this->recommended = (bool) $value;

        return $this;
    }

    /**
     * Set Label Price Attribute
     * @param $value
     * @return $this
     */
    public function setLabelPriceAttribute($value): self
    {
        if (!$value instanceof LabelPrice) {
            $value = new LabelPrice($value);
        }
        $this->labelPrice = $value;

        return $this;
    }

    /**
     * Set Package Restrictions Attribute
     * @param $value
     * @return $this
     */
    public function setPackageRestrictionsAttribute($value): self
    {
        if (!$value instanceof PackageRestriction) {
            $value = new PackageRestriction($value);
        }

        $this->packageRestrictions = $value;

        return $this;
    }

    /**
     * Set Handover Details Attribute
     * @param $value
     * @return $this
     */
    public function setHandoverDetailsAttribute($value): self
    {
        if (!$value instanceof HandoverDetails) {
            $value = new HandoverDetails($value);
        }

        $this->handoverDetails = $value;

        return $this;
    }
}
