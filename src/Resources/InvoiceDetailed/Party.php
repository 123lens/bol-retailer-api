<?php
namespace Budgetlens\BolRetailerApi\Resources\InvoiceDetailed;

use Budgetlens\BolRetailerApi\Resources\BaseResource;
use Illuminate\Support\Collection;

class Party extends BaseResource
{
    public $PartyIdentification;
    public $PartyName;
    public $PostalAddress;
    public $PartyTaxScheme;
    public $PartyLegalEntity;
    public $Contact;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    public function setPartyIdentificationAttribute($value): self
    {
        $items = new Collection();

        collect($value)->each(function ($item) use ($items) {
            if (is_object($item) && property_exists($item, 'ID')) {
                $item = $item->ID;
            }

            if (!$item instanceof PartyIdentification) {
                $item = new PartyIdentification($item);
            }
            $items->push($item);
        });

        $this->PartyIdentification = $items;

        return $this;
    }

    public function setPartyNameAttribute($value): self
    {
        $items = new Collection();

        collect($value)->each(function ($item) use ($items) {
            if (!$item instanceof PartyName) {
                $item = new PartyName($item);
            }
            $items->push($item);
        });

        $this->PartyName = $items;

        return $this;
    }

    public function setPostalAddressAttribute($value): self
    {
        if (!$value instanceof PostalAddress) {
            $value = new PostalAddress($value);
        }

        $this->PostalAddress = $value;

        return $this;
    }

    public function setPartyTaxSchemeAttribute($value): self
    {
        $items = new Collection();

        collect($value)->each(function ($item) use ($items) {
            if (!$item instanceof PartyTaxScheme) {
                $item = new PartyTaxScheme($item);
            }
            $items->push($item);
        });

        $this->PartyTaxScheme = $items;

        return $this;
    }

    public function setPartyLegalEntityAttribute($value): self
    {
        $items = new Collection();

        collect($value)->each(function ($item) use ($items) {
            if (!$item instanceof PartyLegalEntity) {
                $item = new PartyLegalEntity($item);
            }
            $items->push($item);
        });

        $this->PartyLegalEntity = $items;

        return $this;
    }

    public function setContactAttribute($value): self
    {
        if (!$value instanceof Contact) {
            $value = new Contact($value);
        }

        $this->Contact = $value;

        return $this;
    }
}
