<?php
namespace Budgetlens\BolRetailerApi\Endpoints\RetailerAPI;

use Budgetlens\BolRetailerApi\Endpoints\BaseEndpoint;
use Budgetlens\BolRetailerApi\Resources\Insight;
use Budgetlens\BolRetailerApi\Resources\Insights\ForeCast;
use Budgetlens\BolRetailerApi\Resources\Insights\SearchTerm;
use Budgetlens\BolRetailerApi\Resources\PerformanceIndicator;
use Illuminate\Support\Collection;

class Insights extends BaseEndpoint
{
    /**
     * Get Offer Insights
     *
     * @see https://api.bol.com/retailer/public/redoc/v9/retailer.html#operation/get-offer-insights
     * @param string $offerId
     * @param string $period
     * @param int $numberOfPeriods
     * @param array|string[] $name
     * @return Collection
     */
    public function get(
        string $offerId,
        string $period = 'MONTH',
        int $numberOfPeriods = 1,
        array $name = ['PRODUCT_VISITS', 'BUY_BOX_PERCENTAGE']
    ): Collection {
        $parameters = collect([
            'offer-id' => $offerId,
            'period' => $period,
            'number-of-periods' => $numberOfPeriods,
            'name' => $name
        ])->reject(function ($value) {
            return empty($value);
        });

        $response = $this->performApiCall(
            'GET',
            "insights/offer" . $this->buildQueryString($parameters->all())
        );

        $collection = new Collection();

        collect($response->offerInsights)->each(function ($item) use ($collection) {
            $collection->push(new Insight($item));
        });

        return $collection;
    }

    /**
     * Get Performance Indicators
     * @see https://api.bol.com/retailer/public/redoc/v8/retailer.html#operation/get-performance-indicators
     * @param string $year
     * @param string $week
     * @param array $indicators
     * @return Collection
     */
    public function getPerformanceIndicators(string $year, string $week, array $indicators = [])
    {
        $parameters = collect([
            'year' => $year,
            'week' => $week,
            'name' => $indicators
        ])->reject(function ($value) {
            return empty($value);
        });

        $response = $this->performApiCall(
            'GET',
            "insights/performance/indicator" . $this->buildQueryString($parameters->all())
        );

        $collection = new Collection();

        collect($response->performanceIndicators)->each(function ($item) use ($collection) {
            $collection->push(new PerformanceIndicator($item));
        });

        return $collection;
    }

    /**
     * Get Sales Forecast
     * @see https://api.bol.com/retailer/public/redoc/v8/retailer.html#operation/get-sales-forecast
     * @param string $offerId
     * @param int $weeks
     * @return ForeCast
     */
    public function getSalesForecast(string $offerId, int $weeks)
    {
        $parameters = collect([
            'offer-id' => $offerId,
            'weeks-ahead' => $weeks,
        ])->reject(function ($value) {
            return empty($value);
        });

        $response = $this->performApiCall(
            'GET',
            "insights/sales-forecast" . $this->buildQueryString($parameters->all())
        );

        return new ForeCast($response);
    }

    /**
     * Get Search Terms
     * @see https://api.bol.com/retailer/public/redoc/v8/retailer.html#operation/get-search-terms
     * @param string $term
     * @param string $period
     * @param int $numberOfPeriods
     * @param bool $relatedTerms
     * @return SearchTerm
     */
    public function getSearchTerms(string $term, string $period, int $numberOfPeriods = 1, bool $relatedTerms = false)
    {
        $parameters = collect([
            'search-term' => $term,
            'period' => $period,
            'number-of-periods' => $numberOfPeriods,
            'related-search-terms' => $relatedTerms
        ])->reject(function ($value) {
            return is_null($value);
        });

        $response = $this->performApiCall(
            'GET',
            "insights/search-terms" . $this->buildQueryString($parameters->all())
        );

        return new SearchTerm($response->searchTerms);
    }
}
