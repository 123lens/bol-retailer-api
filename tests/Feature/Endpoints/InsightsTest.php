<?php
namespace Budgetlens\BolRetailerApi\Tests\Feature\Endpoints;

use Budgetlens\BolRetailerApi\Resources\Commission;
use Budgetlens\BolRetailerApi\Resources\Insight;
use Budgetlens\BolRetailerApi\Resources\Insights\Country;
use Budgetlens\BolRetailerApi\Resources\Insights\ForeCast;
use Budgetlens\BolRetailerApi\Resources\Insights\Performance\Norm;
use Budgetlens\BolRetailerApi\Resources\Insights\Performance\Score;
use Budgetlens\BolRetailerApi\Resources\Insights\PerformanceDetail;
use Budgetlens\BolRetailerApi\Resources\Insights\Search\RelatedTerm;
use Budgetlens\BolRetailerApi\Resources\Insights\SearchTerm;
use Budgetlens\BolRetailerApi\Resources\PerformanceIndicator;
use Budgetlens\BolRetailerApi\Resources\Reduction;
use Budgetlens\BolRetailerApi\Tests\TestCase;
use Illuminate\Support\Collection;

class InsightsTest extends TestCase
{
    /** @test */
    public function getOfferInsight()
    {
        $this->useMock('200-get-offer-insights.json');

        $offerId = '7aec42a4-8c2b-4c38-ac3c-5e5a3f54341e';
        $period = 'MONTH';
        $numberOfPeriods = 1;
        $name = ['PRODUCT_VISITS', 'BUY_BOX_PERCENTAGE'];

        $insights = $this->client->insights->get($offerId, $period, $numberOfPeriods, $name);
//        print_r($insights);
//        exit;
        $this->assertInstanceOf(Collection::class, $insights);
        $this->assertCount(2, $insights);

        $this->assertInstanceOf(Insight::class, $insights->first());
        $this->assertSame('BUY_BOX_PERCENTAGE', $insights->first()->name);
        $this->assertSame('percentage', $insights->first()->type);
        $this->assertSame(0, $insights->first()->total);
        $this->assertInstanceOf(Collection::class, $insights->first()->countries);
        $this->assertCount(2, $insights->first()->countries);
        $this->assertSame('BE', $insights->first()->countries->first()->countryCode);
        $this->assertSame(100.0, $insights->first()->countries->first()->value);
        $this->assertSame('NL', $insights->first()->countries->last()->countryCode);
        $this->assertSame(92.9, $insights->first()->countries->last()->value);

        $this->assertInstanceOf(Collection::class, $insights->first()->periods);
        $this->assertCount(1, $insights->first()->periods);
        $this->assertSame(12, $insights->first()->periods->first()->month);
        $this->assertSame(2019, $insights->first()->periods->first()->year);
        $this->assertSame(0, $insights->first()->periods->first()->total);

        $this->assertInstanceOf(Collection::class, $insights->first()->periods->first()->countries);
        $this->assertCount(2, $insights->first()->periods->first()->countries);
        $this->assertSame('BE', $insights->first()->periods->first()->countries->first()->countryCode);
        $this->assertSame(100.0, $insights->first()->periods->first()->countries->first()->value);
        $this->assertSame('NL', $insights->first()->periods->first()->countries->last()->countryCode);
        $this->assertSame(92.9, $insights->first()->periods->first()->countries->last()->value);



        $this->assertInstanceOf(Insight::class, $insights->last());
        $this->assertSame('PRODUCT_VISITS', $insights->last()->name);
        $this->assertSame('count', $insights->last()->type);
        $this->assertSame(72.0, $insights->last()->total);
        $this->assertInstanceOf(Collection::class, $insights->last()->countries);
        $this->assertCount(2, $insights->last()->countries);
        $this->assertSame('BE', $insights->last()->countries->first()->countryCode);
        $this->assertSame(7.0, $insights->last()->countries->first()->value);
        $this->assertSame('NL', $insights->last()->countries->last()->countryCode);
        $this->assertSame(65.0, $insights->last()->countries->last()->value);

        $this->assertInstanceOf(Collection::class, $insights->last()->periods);
        $this->assertCount(1, $insights->last()->periods);
        $this->assertSame(12, $insights->last()->periods->first()->month);
        $this->assertSame(2019, $insights->last()->periods->first()->year);
        $this->assertSame(72.0, $insights->last()->periods->first()->total);

        $this->assertInstanceOf(Collection::class, $insights->last()->periods->first()->countries);
        $this->assertCount(2, $insights->last()->periods->first()->countries);
        $this->assertSame('BE', $insights->last()->periods->first()->countries->first()->countryCode);
        $this->assertSame(7.0, $insights->last()->periods->first()->countries->first()->value);
        $this->assertSame('NL', $insights->last()->periods->first()->countries->last()->countryCode);
        $this->assertSame(65.0, $insights->last()->periods->first()->countries->last()->value);
    }

    /** @test */
    public function getPerformanceIndicator()
    {
        $this->useMock('200-get-performance-indicators-all-indicators.json');

        $year = 2019;
        $week = 10;
        $indicators = ['CANCELLATIONS', 'FULFILMENT', 'PHONE_AVAILABILITY', 'CASE_ITEM_RATIO',
            'TRACK_AND_TRACE', 'RETURNS', 'REVIEWS'
        ];

        $indicators = $this->client->insights->getPerformanceIndicators($year, $week, $indicators);
        $this->assertInstanceOf(Collection::class, $indicators);
        $this->assertCount(7, $indicators);
        $this->assertInstanceOf(PerformanceIndicator::class, $indicators->first());
        $firstIndicator = $indicators->first();
        $this->assertSame('CANCELLATIONS', $firstIndicator->name);
        $this->assertSame('PERCENTAGE', $firstIndicator->type);
        $this->assertInstanceOf(PerformanceDetail::class, $firstIndicator->details);
        $this->assertSame(10, $firstIndicator->details->week);
        $this->assertSame(2019, $firstIndicator->details->year);
        $this->assertInstanceOf(Score::class, $firstIndicator->details->score);
        // performance indicator achieved
        $this->assertSame(false, $firstIndicator->details->score->conforms);
        $this->assertSame(8, $firstIndicator->details->score->numerator);
        $this->assertSame(88, $firstIndicator->details->score->denominator);
        $this->assertSame(0.88, $firstIndicator->details->score->value);
        $this->assertSame(0.86, $firstIndicator->details->score->distanceToNorm);
        $this->assertInstanceOf(Norm::class, $firstIndicator->details->norm);
        $this->assertSame('<=', $firstIndicator->details->norm->condition);
        $this->assertSame(0.02, $firstIndicator->details->norm->value);

        $performanceAchieved = $indicators->where('name', 'CASE_ITEM_RATIO')->first();
        $this->assertSame('CASE_ITEM_RATIO', $performanceAchieved->name);
        $this->assertSame('PERCENTAGE', $performanceAchieved->type);
        $this->assertInstanceOf(PerformanceDetail::class, $performanceAchieved->details);
        $this->assertSame(10, $performanceAchieved->details->week);
        $this->assertSame(2019, $performanceAchieved->details->year);
        $this->assertInstanceOf(Score::class, $performanceAchieved->details->score);
        // performance indicator achieved
        $this->assertSame(true, $performanceAchieved->details->score->conforms);
        $this->assertSame(0, $performanceAchieved->details->score->numerator);
        $this->assertSame(54, $performanceAchieved->details->score->denominator);
        $this->assertSame(0.0, $performanceAchieved->details->score->value);
        $this->assertSame(0.05, $performanceAchieved->details->score->distanceToNorm);
        $this->assertInstanceOf(Norm::class, $performanceAchieved->details->norm);
        $this->assertSame('<=', $performanceAchieved->details->norm->condition);
        $this->assertSame(0.05, $performanceAchieved->details->norm->value);
    }

    /** @test */
    public function getSalesForecast()
    {
        $this->useMock('200-get-sales-forecast-12-weeks.json');

        $offerId = '91c28f60-ed1d-4b85-e053-828b620a4ed5';
        $weeks = 12;
        $forecast = $this->client->insights->getSalesForecast($offerId, $weeks);

        $this->assertInstanceOf(ForeCast::class, $forecast);
        $this->assertSame('SALES_FORECAST', $forecast->name);
        $this->assertSame('decimal', $forecast->type);
        $this->assertSame(10.0, $forecast->minimum);
        $this->assertSame(100.0, $forecast->maximum);
        $this->assertInstanceOf(Collection::class, $forecast->countries);
        $this->assertCount(2, $forecast->countries);
        $this->assertInstanceOf(Country::class, $forecast->countries->first());
        $this->assertSame('BE', $forecast->countries->first()->countryCode);
        $this->assertSame(0.0, $forecast->countries->first()->minimum);
        $this->assertSame(10.0, $forecast->countries->first()->maximum);
        $this->assertSame('NL', $forecast->countries->last()->countryCode);
        $this->assertSame(10.0, $forecast->countries->last()->minimum);
        $this->assertSame(100.0, $forecast->countries->last()->maximum);

        $this->assertInstanceOf(Collection::class, $forecast->periods);
        $this->assertCount(12, $forecast->periods);
        $this->assertSame(1, $forecast->periods->first()->weeksAhead);
        $this->assertIsInt($forecast->periods->first()->weeksAhead);
        $this->assertIsFloat($forecast->periods->first()->minimum);
        $this->assertIsFloat($forecast->periods->first()->maximum);
        $this->assertSame(0.0, $forecast->periods->first()->minimum);
        $this->assertSame(10.0, $forecast->periods->first()->maximum);
        $this->assertInstanceOf(Collection::class, $forecast->periods->first()->countries);

        $this->assertSame('BE', $forecast->periods->first()->countries->first()->countryCode);
        $this->assertSame(0.0, $forecast->periods->first()->countries->first()->minimum);
        $this->assertSame(10.0, $forecast->periods->first()->countries->first()->maximum);
        $this->assertSame('NL', $forecast->periods->first()->countries->last()->countryCode);
        $this->assertSame(0.0, $forecast->periods->first()->countries->last()->minimum);
        $this->assertSame(10.0, $forecast->periods->first()->countries->last()->maximum);
        $this->assertIsFloat($forecast->periods->first()->countries->last()->minimum);
        $this->assertIsFloat($forecast->periods->first()->countries->last()->maximum);
    }

    /** @test */
    public function getSearchTermsWithRelated()
    {
        $this->useMock('200-get-search-terms-with-related.json');

        $term = 'Potter';
        $period = 'WEEK';
        $weeks = 2;
        $related = false;

        $searchTerm = $this->client->insights->getSearchTerms($term, $period, $weeks, $related);
        $this->assertInstanceOf(SearchTerm::class, $searchTerm);
        $this->assertSame('Potter', $searchTerm->searchTerm);
        $this->assertSame('count', $searchTerm->type);
        $this->assertSame(19, $searchTerm->total);

        $this->assertInstanceOf(Collection::class, $searchTerm->countries);
        $this->assertCount(2, $searchTerm->countries);
        $this->assertSame('NL', $searchTerm->countries->first()->countryCode);
        $this->assertSame(15, $searchTerm->countries->first()->value);
        $this->assertSame('BE', $searchTerm->countries->last()->countryCode);
        $this->assertSame(4, $searchTerm->countries->last()->value);

        $this->assertInstanceOf(Collection::class, $searchTerm->periods);
        $this->assertCount(2, $searchTerm->periods);
        $this->assertSame(32, $searchTerm->periods->first()->week);
        $this->assertSame(2021, $searchTerm->periods->first()->year);
        $this->assertSame(16, $searchTerm->periods->first()->total);
        $this->assertInstanceOf(Collection::class, $searchTerm->periods->first()->countries);
        $this->assertCount(2, $searchTerm->periods->first()->countries);
        $this->assertSame('NL', $searchTerm->periods->first()->countries->first()->countryCode);
        $this->assertSame(12, $searchTerm->periods->first()->countries->first()->value);
        $this->assertSame('BE', $searchTerm->periods->first()->countries->last()->countryCode);
        $this->assertSame(4, $searchTerm->periods->first()->countries->last()->value);

        $this->assertInstanceOf(Collection::class, $searchTerm->relatedSearchTerms);
        $this->assertCount(10, $searchTerm->relatedSearchTerms);
        $this->assertInstanceOf(RelatedTerm::class, $searchTerm->relatedSearchTerms->first());
        $this->assertSame(99, $searchTerm->relatedSearchTerms->first()->total);
        $this->assertSame('plotter', $searchTerm->relatedSearchTerms->first()->searchTerm);
    }


    /** @test */
    public function getSearchTermsWithoutRelated()
    {
        $this->useMock('200-get-search-terms-without-related.json');

        $term = 'Potter';
        $period = 'WEEK';
        $weeks = 2;
        $related = false;

        $searchTerm = $this->client->insights->getSearchTerms($term, $period, $weeks, $related);
        $this->assertInstanceOf(SearchTerm::class, $searchTerm);
        $this->assertSame('Potter', $searchTerm->searchTerm);
        $this->assertSame('count', $searchTerm->type);
        $this->assertSame(19, $searchTerm->total);

        $this->assertInstanceOf(Collection::class, $searchTerm->countries);
        $this->assertCount(2, $searchTerm->countries);
        $this->assertSame('NL', $searchTerm->countries->first()->countryCode);
        $this->assertSame(15, $searchTerm->countries->first()->value);
        $this->assertSame('BE', $searchTerm->countries->last()->countryCode);
        $this->assertSame(4, $searchTerm->countries->last()->value);

        $this->assertInstanceOf(Collection::class, $searchTerm->periods);
        $this->assertCount(2, $searchTerm->periods);
        $this->assertSame(32, $searchTerm->periods->first()->week);
        $this->assertSame(2021, $searchTerm->periods->first()->year);
        $this->assertSame(16, $searchTerm->periods->first()->total);
        $this->assertInstanceOf(Collection::class, $searchTerm->periods->first()->countries);
        $this->assertCount(2, $searchTerm->periods->first()->countries);
        $this->assertSame('NL', $searchTerm->periods->first()->countries->first()->countryCode);
        $this->assertSame(12, $searchTerm->periods->first()->countries->first()->value);
        $this->assertSame('BE', $searchTerm->periods->first()->countries->last()->countryCode);
        $this->assertSame(4, $searchTerm->periods->first()->countries->last()->value);
    }
}
