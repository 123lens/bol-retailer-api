<?php
namespace Budgetlens\BolRetailerApi\Tests\Feature\Endpoints\RetailerApi;

use Budgetlens\BolRetailerApi\Exceptions\ValidationException;
use Budgetlens\BolRetailerApi\Requests\ListProductsRequest;
use Budgetlens\BolRetailerApi\Resources\Filters\Category;
use Budgetlens\BolRetailerApi\Resources\Filters\Filter;
use Budgetlens\BolRetailerApi\Resources\Filters\FilterRange;
use Budgetlens\BolRetailerApi\Resources\Filters\FilterValue;
use Budgetlens\BolRetailerApi\Resources\FiltersList;
use Budgetlens\BolRetailerApi\Resources\Product;
use Budgetlens\BolRetailerApi\Resources\ProductList;
use Budgetlens\BolRetailerApi\Tests\TestCase;
use Illuminate\Support\Collection;

class ProductsTest extends TestCase
{
    /** @test */
    public function getProductsListWithFilterRange()
    {
        $this->useMock('200-list-products-filter-range.json');

        $request = (new ListProductsRequest([
            'countryCode' => 'BE',
            'categoryId' => 38386,
            'searchTerm' => 'STICKERS',
            'filterRanges' => [
                [
                    'rangeId' => 'RATING',
                    'min' => 1,
                    'max' => 4
                ]
            ],
        ]))->addHeader('Accept-Language', 'fr-BE');

        $result = $this->client->products->list($request);
        $this->assertInstanceOf(ProductList::class, $result);
        $this->assertCount(2, $result->products);
        $this->assertInstanceOf(Product::class, $result->products->first());
        $this->assertCount(1, $result->products->first()->eans);
        $this->assertSame("Fille avec ballon, autocollant de voiture", $result->products->first()->title);
        $this->assertSame('9343929000678', $result->products->first()->eans->get(0));
        $this->assertSame('8020912014300', $result->products->last()->eans->get(0));
        $this->assertSame('POPULARITY', $result->sort);
    }

    /** @test */
    public function getProductsListByCategory()
    {
        $this->useMock('200-list-products-by-category.json');

        $request = (new ListProductsRequest([
            'countryCode' => 'NL',
            'categoryId' => 10505,
            'sort' => 'RELEVANCE'
        ]))->addHeader('Accept-Language', 'nl-NL');

        $result = $this->client->products->list($request);

        $this->assertInstanceOf(ProductList::class, $result);
        $this->assertCount(2, $result->products);
        $this->assertInstanceOf(Product::class, $result->products->first());
        $this->assertCount(2, $result->products->first()->eans);
        $this->assertSame("Robotime modelbouw Miniatuur bouwpakket Cathy's Flower House hout/papier/kunststof - 195mm hoog x 175mm breed x 175mm diep - met lampje", $result->products->first()->title);
        $this->assertSame('8718274546071', $result->products->first()->eans->get(0));
        $this->assertSame('6946785108736', $result->products->first()->eans->get(1));
        $this->assertSame('RELEVANCE', $result->sort);
    }

    /** @test */
    public function getProductsListBySearchTermBelgium()
    {
        $this->useMock('200-list-products-by-search-term-belgium.json');

        $request = (new ListProductsRequest([
            'countryCode' => 'BE',
            'searchTerm' => 'LEGO',
            'filterRanges' => [
                [
                    'rangeId' => 'RATING',
                    'min' => 1,
                    'max' => 4
                ]
            ],
            "sort" => "RELEVANCE",
        ]))->addHeader('Accept-Language', 'nl-BE');
        $result = $this->client->products->list($request);

        $this->assertInstanceOf(ProductList::class, $result);
        $this->assertCount(2, $result->products);
        $this->assertInstanceOf(Product::class, $result->products->first());
        $this->assertCount(1, $result->products->first()->eans);
        $this->assertSame("LEGO Marvel Super Heroes 2 - Switch", $result->products->first()->title);
        $this->assertCount(3, $result->products->get(1)->eans);
        $this->assertSame('5051888111673', $result->products->get(1)->eans->get(0));
        $this->assertSame('5051888079454', $result->products->get(1)->eans->get(1));
        $this->assertSame('5051895082645', $result->products->get(1)->eans->get(2));
        $this->assertSame('RELEVANCE', $result->sort);
    }

    /** @test */
    public function getProductListFiltersForCategory()
    {
        $this->useMock('200-list-products-filters-for-category.json');

        $request = new ListProductsRequest([
            'countryCode' => 'NL',
            'categoryId' => 10505,
        ]);

        $result = $this->client->products->listFilters($request);

        $this->assertInstanceOf(FiltersList::class, $result);
        $this->assertInstanceOf(Category::class, $result->categoryData);
        $this->assertSame('Categorieën', $result->categoryData->categoryName);
        $this->assertInstanceOf(Collection::class, $result->categoryData->categoryValues);
        $this->assertCount(2, $result->categoryData->categoryValues);
        $this->assertSame('38386', $result->categoryData->categoryValues->first()->categoryValueId);
        $this->assertSame('Stickers & Tapes', $result->categoryData->categoryValues->first()->categoryValueName);
        $this->assertInstanceOf(Collection::class, $result->filterRanges);
        $this->assertCount(2, $result->filterRanges);
        $this->assertInstanceOf(FilterRange::class, $result->filterRanges->first());
        $this->assertSame('PRICE', $result->filterRanges->first()->rangeId);
        $this->assertSame('Prijs', $result->filterRanges->first()->rangeName);
        $this->assertSame(0.99, $result->filterRanges->first()->min);
        $this->assertSame(4599, $result->filterRanges->first()->max);
        $this->assertSame('EUR', $result->filterRanges->first()->unit);
        $this->assertInstanceOf(Collection::class, $result->filters);
        $this->assertCount(2, $result->filters);
        $this->assertInstanceOf(Filter::class, $result->filters->first());
        $this->assertSame('Meest populair bij', $result->filters->first()->filterName);
        $this->assertInstanceOf(Collection::class, $result->filters->first()->filterValues);
        $this->assertCount(2, $result->filters->first()->filterValues);
        $this->assertInstanceOf(FilterValue::class, $result->filters->first()->filterValues->first());
        $this->assertSame('8071', $result->filters->first()->filterValues->first()->filterValueId);
        $this->assertSame('Volwassenen', $result->filters->first()->filterValues->first()->filterValueName);
    }


    /** @test */
    public function getProductListFiltersForSearchTerm()
    {
        $this->useMock('200-list-products-filters-for-search-term.json');

        $request = new ListProductsRequest([
            'countryCode' => 'BE',
            'searchTerm' => 'lego',
        ]);

        $result = $this->client->products->listFilters($request);

        $this->assertInstanceOf(FiltersList::class, $result);
        $this->assertInstanceOf(Category::class, $result->categoryData);
        $this->assertSame('Categorieën', $result->categoryData->categoryName);
        $this->assertInstanceOf(Collection::class, $result->categoryData->categoryValues);
        $this->assertCount(2, $result->categoryData->categoryValues);
        $this->assertSame('7934', $result->categoryData->categoryValues->first()->categoryValueId);
        $this->assertSame('Speelgoed', $result->categoryData->categoryValues->first()->categoryValueName);
        $this->assertInstanceOf(Collection::class, $result->filterRanges);
        $this->assertCount(2, $result->filterRanges);
        $this->assertInstanceOf(FilterRange::class, $result->filterRanges->first());
        $this->assertSame('PRICE', $result->filterRanges->first()->rangeId);
        $this->assertSame('Prijs', $result->filterRanges->first()->rangeName);
        $this->assertSame(0.99, $result->filterRanges->first()->min);
        $this->assertSame(4599, $result->filterRanges->first()->max);
        $this->assertSame('EUR', $result->filterRanges->first()->unit);
        $this->assertInstanceOf(Collection::class, $result->filters);
        $this->assertCount(2, $result->filters);
        $this->assertInstanceOf(Filter::class, $result->filters->first());
        $this->assertSame('Serie', $result->filters->first()->filterName);
        $this->assertInstanceOf(Collection::class, $result->filters->first()->filterValues);
        $this->assertCount(2, $result->filters->first()->filterValues);
        $this->assertInstanceOf(FilterValue::class, $result->filters->first()->filterValues->first());
        $this->assertSame('4279587082', $result->filters->first()->filterValues->first()->filterValueId);
        $this->assertSame('LEGO Star Wars', $result->filters->first()->filterValues->first()->filterValueName);
    }

}
