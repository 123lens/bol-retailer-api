<?php
namespace Budgetlens\BolRetailerApi\Tests\Feature\Endpoints\RetailerApi;

use Budgetlens\BolRetailerApi\Requests\ListProductsRequest;
use Budgetlens\BolRetailerApi\Resources\Product;
use Budgetlens\BolRetailerApi\Resources\ProductList;
use Budgetlens\BolRetailerApi\Tests\TestCase;

class ProductsTest extends TestCase
{
    /** @test */
    public function getProductsListWithFilterRange()
    {
//        $this->useMock('200-list-products-by-category.json');

        $request = new ListProductsRequest([
            'countryCode' => 'BE',
            'categoryId' => 38386,
            'searchTerm' => 'STICKERS',
            'filterRanges' => [
                'rangeId' => 'RATING',
                'min' => 1,
                'max' => 4
            ],
        ]);

        print_r($request);
        exit;

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
}
