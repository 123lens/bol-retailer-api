<?php
namespace Budgetlens\BolRetailerApi\Resources\InvoiceDetailed;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class PostalAddress extends BaseResource
{
    public $BuildingNumber;
    public $CityName;
    public $Country;
    public $PostalZone;
    public $StreetName;


    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    public function setBuildingNumberAttribute($value): self
    {
        if (is_object($value)) {
            $value = $value->value ?? null;
        }

        $this->BuildingNumber = $value;

        return $this;
    }

    public function setCityNameAttribute($value): self
    {
        if (is_object($value)) {
            $value = $value->value ?? null;
        }

        $this->CityName = $value;

        return $this;
    }

    public function setPostalZoneAttribute($value): self
    {
        if (is_object($value)) {
            $value = $value->value ?? null;
        }

        $this->PostalZone = $value;

        return $this;
    }

    public function setStreetNameAttribute($value): self
    {
        if (is_object($value)) {
            $value = $value->value ?? null;
        }

        $this->StreetName = $value;

        return $this;
    }
}
