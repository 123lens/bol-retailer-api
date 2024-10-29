<?php
namespace Budgetlens\BolRetailerApi\Tests\Feature\Endpoints\RetailerApi;

use Budgetlens\BolRetailerApi\Requests\ListProductsRequest;
use Budgetlens\BolRetailerApi\Requests\ProductPlacementRequest;
use Budgetlens\BolRetailerApi\Resources\Filters\Category;
use Budgetlens\BolRetailerApi\Resources\Filters\Filter;
use Budgetlens\BolRetailerApi\Resources\Filters\FilterRange;
use Budgetlens\BolRetailerApi\Resources\Filters\FilterValue;
use Budgetlens\BolRetailerApi\Resources\FiltersList;
use Budgetlens\BolRetailerApi\Resources\Product;
use Budgetlens\BolRetailerApi\Resources\ProductIds;
use Budgetlens\BolRetailerApi\Resources\ProductList;
use Budgetlens\BolRetailerApi\Resources\ProductPlacement;
use Budgetlens\BolRetailerApi\Resources\ProductRatings;
use Budgetlens\BolRetailerApi\Tests\TestCase;
use DateTimeImmutable;
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

    /** @test */
    public function getProductAssetsPrimaryImage()
    {
        $this->useMock('200-get-product-assets-primary-image.json');

        $eancode = '5035223124276';
        $result = $this->client->products->getAssets($eancode, 'PRIMARY');
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(1, $result);
        $this->assertInstanceOf(Product\Asset::class, $result->first());
        $this->assertSame('PRIMARY', $result->first()->usage);
        $this->assertSame(0, $result->first()->order);
        $this->assertInstanceOf(Collection::class, $result->first()->variants);
        $this->assertCount(2, $result->first()->variants);
        $this->assertInstanceOf(Product\Assets\Variant::class, $result->first()->variants->first());
        $this->assertSame('small', $result->first()->variants->first()->size);
        $this->assertSame(250, $result->first()->variants->first()->width);
        $this->assertSame(200, $result->first()->variants->first()->height);
        $this->assertSame('image/jpeg', $result->first()->variants->first()->mimeType);
        $this->assertSame('https://media.s-bol.com/mkjdlmV9w5R0/8lB005/250x200.jpg', $result->first()->variants->first()->url);
    }

    /** @test */
    public function getBestOfferSoldBe()
    {
        $this->useMock('200-get-product-best-offer-sold-be.json');

        $result = $this->client->products->getCompetingOffers(
            eancode: '5035223124276',
            countryCode: 'BE',
            bestOfferOnly: true,
            condition: 'MODERATE'
        );
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(1, $result);
        $this->assertInstanceOf(Product\CompetingOffer::class, $result->first());
        $this->assertSame('908b6d06-2067-4klf-8490-c21d0c233e61', $result->first()->offerId);
        $this->assertSame('8748934', $result->first()->retailerId);
        $this->assertSame('BE', $result->first()->countryCode);
        $this->assertSame(true, $result->first()->bestOffer);
        $this->assertSame(36.59, $result->first()->price);
        $this->assertSame('FBR', $result->first()->fulfilmentMethod);
        $this->assertSame('MODERATE', $result->first()->condition);
        $this->assertSame('19:00', $result->first()->ultimateOrderTime);
        $this->assertInstanceOf(DateTimeImmutable::class, $result->first()->minDeliveryDate);
        $this->assertInstanceOf(DateTimeImmutable::class, $result->first()->maxDeliveryDate);
        $this->assertSame('2022-05-18', $result->first()->minDeliveryDate->format('Y-m-d'));
        $this->assertSame('2022-05-23', $result->first()->maxDeliveryDate->format('Y-m-d'));
    }

    /** @test */
    public function getAllAvailableOffersNL()
    {
        $this->useMock('200-get-product-all-offer-sold-nl.json');

        $result = $this->client->products->getCompetingOffers(
            eancode: '9789463160315',
        );

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
        $this->assertInstanceOf(Product\CompetingOffer::class, $result->first());
        $this->assertSame('228b6d06-2067-4cef-8447-c21d0c233e61', $result->first()->offerId);
        $this->assertSame('738903', $result->first()->retailerId);
        $this->assertSame('NL', $result->first()->countryCode);
        $this->assertSame(true, $result->first()->bestOffer);
        $this->assertSame(41.5, $result->first()->price);
        $this->assertSame('FBB', $result->first()->fulfilmentMethod);
        $this->assertSame('NEW', $result->first()->condition);
        $this->assertSame('23:59', $result->first()->ultimateOrderTime);
        $this->assertInstanceOf(DateTimeImmutable::class, $result->first()->minDeliveryDate);
        $this->assertInstanceOf(DateTimeImmutable::class, $result->first()->maxDeliveryDate);
        $this->assertSame('2022-10-20', $result->first()->minDeliveryDate->format('Y-m-d'));
        $this->assertSame('2022-10-21', $result->first()->maxDeliveryDate->format('Y-m-d'));
    }

    /** @test */
    public function getProductCategoriesAndUrlNL()
    {
        $this->useMock('200-get-product-url-and-categories-nl.json');

        $result = $this->client->products->getPlacement(
            (new ProductPlacementRequest([
                'ean' => '4042448804839'
            ]))
                ->addQuery('countryCode', 'NL')
                ->addHeader('Accept-Language', 'nl')
        );
        $this->assertInstanceOf(ProductPlacement::class, $result);
        $this->assertInstanceOf(Collection::class, $result->categories);
        $this->assertCount(1, $result->categories);
        $this->assertSame('https://www.acc2.bol.com/nl/nl/p/tesa-afplakband-50m-x-19mm/9200000010397028/', $result->url);
        $this->assertInstanceOf(Product\PlacementCategory::class, $result->categories->first());
        $this->assertSame('13155', $result->categories->first()->categoryId);
        $this->assertSame('Klussen', $result->categories->first()->categoryName);
        $this->assertInstanceOf(Collection::class, $result->categories->first()->subcategories);
        $this->assertCount(1, $result->categories->first()->subcategories);
        $this->assertInstanceOf(Product\PlacementSubCategory::class, $result->categories->first()->subcategories->first());
        $this->assertSame('13261', $result->categories->first()->subcategories->first()->id);
        $this->assertSame('Verfspullen', $result->categories->first()->subcategories->first()->name);
    }

    /** @test */
    public function getProductIdsByEancode()
    {
        $this->useMock('200-get-product-ids-by-eancode.json');

        $eancode = '8712836327641';
        $result = $this->client->products->getProductIds($eancode);

        $this->assertInstanceOf(ProductIds::class, $result);
        $this->assertSame('9200000045327288', $result->bolProductId);
        $this->assertInstanceOf(Collection::class, $result->eans);
        $this->assertCount(2, $result->eans);
        $this->assertSame('8712836327641', $result->eans->get(0));
        $this->assertSame('8712836327658', $result->eans->get(1));
    }

    /** @test */
    public function getProductRatingsByEan()
    {
        $this->useMock('200-get-product-ratings-by-eancode.json');

        $eancode = '5030917181740';
        $result = $this->client->products->getProductRatings($eancode);

        $this->assertInstanceOf(ProductRatings::class, $result);
        $this->assertCount(5, $result->ratings);
        $this->assertInstanceOf(Product\ProductRating::class, $result->ratings->first());
        $this->assertSame(5, $result->ratings->first()->rating);
        $this->assertSame(488, $result->ratings->first()->count);
        $this->assertSame(577, $result->getTotalVotes());
        $this->assertSame(4.77, $result->getAverage());
    }
}
