<?php
namespace Budgetlens\BolRetailerApi\Resources\Insights;

use Budgetlens\BolRetailerApi\Resources\BaseResource;
use Budgetlens\BolRetailerApi\Resources\Insights\Forecast\Period;
use Illuminate\Support\Collection;

class ForeCast extends BaseResource
{
    public $name;
    public $type;
    public $minimum;
    public $maximum;

    public $countries;
    public $periods;

    public function __construct($attributes = [])
    {
        $this->minimum = 0;
        $this->maximum = 0;
        $this->countries = [];
        $this->periods = [];

        parent::__construct($attributes);
    }

    public function setTotalAttribute($value): self
    {
        $this->minimum = (double)$value->minimum ?? 0;
        $this->maximum = (double)$value->maximum ?? 0;

        return $this;
    }

    public function setCountriesAttribute($value): self
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

    public function setPeriodsAttribute($value): self
    {
        $items = new Collection();
        collect($value)->each(function ($item) use ($items) {
            if (!$item instanceof Period) {
                $item = new Period($item);
            }
            $items->push($item);
        });

        $this->periods = $items;

        return $this;
    }

    public function toArray(): array
    {
        return collect(parent::toArray())
            ->when(!is_null($this->countries), function ($collection) {
                $countries = collect($this->countries)->map(function ($item) {
                    return $item->toArray();
                });
                return $collection->put('countries', $countries->all());
            })
            ->when(!is_null($this->periods), function ($collection) {
                $periods = collect($this->periods)->map(function ($item) {
                    return $item->toArray();
                });
                return $collection->put('periods', $periods->all());
            })
            ->reject(function ($value) {
                return is_null($value);
            })
            ->all();
    }
}
