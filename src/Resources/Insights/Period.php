<?php
namespace Budgetlens\BolRetailerApi\Resources\Insights;

use Budgetlens\BolRetailerApi\Resources\BaseResource;
use Illuminate\Support\Collection;

class Period extends BaseResource
{
    public $day;
    public $week;
    public $month;
    public $year;
    public $total;
    public $countries;

    public function __construct($attributes = [])
    {
        $this->countries = [];
        $this->total = 0;
        parent::__construct($attributes);
    }

    public function setPeriodAttribute($value): self
    {
        $day = $value->day ?? null;
        $week = $value->week ?? null;
        $month = $value->month ?? null;
        $year = $value->year ?? null;

        $this->day = (int)$day ?? null;
        $this->week = (int)$week ?? null;
        $this->month = (int)$month ?? null;
        $this->year = (int)$year ?? null;

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

    public function toArray(): array
    {
        return collect(parent::toArray())
            ->when(!is_null($this->countries), function ($collection) {
                $countries = collect($this->countries)->map(function ($item) {
                    return $item->toArray();
                });
                return $collection->put('countries', $countries->all());
            })
            ->reject(function ($value) {
                return is_null($value);
            })
            ->all();
    }
}
