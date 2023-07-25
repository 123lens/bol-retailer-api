<?php
namespace Budgetlens\BolRetailerApi\Tests\Feature\Endpoints\RetailerApi;

use Budgetlens\BolRetailerApi\Requests\Products\ListProductsRequest;
use Budgetlens\BolRetailerApi\Resources\Inbound;
use Budgetlens\BolRetailerApi\Resources\InboundPackinglist;
use Budgetlens\BolRetailerApi\Resources\InboundProductLabels;
use Budgetlens\BolRetailerApi\Resources\InboundShippingLabel;
use Budgetlens\BolRetailerApi\Resources\ProcessStatus;
use Budgetlens\BolRetailerApi\Resources\Product;
use Budgetlens\BolRetailerApi\Resources\ProductList;
use Budgetlens\BolRetailerApi\Resources\Timeslot;
use Budgetlens\BolRetailerApi\Resources\Transporter;
use Budgetlens\BolRetailerApi\Tests\TestCase;
use Budgetlens\BolRetailerApi\Types\LabelFormat;
use Illuminate\Support\Collection;

class ProductsTest extends TestCase
{
    /** @test */
    public function getProductsList()
    {
    //        $this->useMock('200-get-inbounds.json');
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
    }
}
