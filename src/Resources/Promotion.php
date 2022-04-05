<?php
namespace Budgetlens\BolRetailerApi\Resources;

use Budgetlens\BolRetailerApi\Resources\Promotions\Campaign;
use Illuminate\Support\Collection;

class Promotion extends BaseResource
{
    public $promotionId;
    public $title;
    public $startDateTime;
    public $endDateTime;
    public $countries;
    public $promotionType;
    public $retailerSpecificPromotion;
    public $campaign;

    public function setStartDateTimeAttribute($value): self
    {
        if (!$value instanceof \DateTime) {
            $value = new \DateTime($value);
        }

        $this->startDateTime = $value;

        return $this;
    }

    public function setEndDateTimeAttribute($value): self
    {
        if (!$value instanceof \DateTime) {
            $value = new \DateTime($value);
        }

        $this->endDateTime = $value;

        return $this;
    }


    public function setCountriesAttribute($value)
    {
        $items = new Collection();

        collect($value)->each(function ($item) use ($items) {
            if (!$item instanceof Country) {
                $item = new Country($item);
            }

            $items->push($item);
        });

        $this->countries = $items;

        return $this;
    }

    public function setRetailerSpecificPromotionAttribute($value): self
    {
        $this->retailerSpecificPromotion = (bool) $value;

        return $this;
    }

    public function setCampaignAttribute($value): self
    {
        if (!$value instanceof Campaign) {
            $value = new Campaign($value);
        }

        $this->campaign = $value;

        return $this;
    }
}
