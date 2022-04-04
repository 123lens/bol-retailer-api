<?php
namespace Budgetlens\BolRetailerApi\Tests\Feature\Endpoints\RetailerApi;

use Budgetlens\BolRetailerApi\Resources\Commission;
use Budgetlens\BolRetailerApi\Resources\Reduction;
use Budgetlens\BolRetailerApi\Tests\TestCase;
use Illuminate\Support\Collection;

class CommissionsTest extends TestCase
{
    /** @test */
    public function getBulkCommissions()
    {
        $this->useMock('200-get-all-commissions-bulk.json');

        $items = [
            [
                "ean" => "8712626055150",
                "condition" => "NEW",
                "unitPrice" => "34.99"
            ], [
                "ean" => "8804269223123",
                "condition" => "NEW",
                "unitPrice" => "699.95"
            ], [
                "ean" => "8712626055143",
                "condition" => "GOOD",
                "unitPrice" => "24.50"
            ], [
                "ean" => "0604020064587",
                "condition" => "NEW",
                "unitPrice" => "24.95"
            ], [
                "ean" => "8718526069334",
                "condition" => "NEW",
                "unitPrice" => "25.00"
            ]
        ];

        $commissions = $this->client->commission->list($items);

        $this->assertInstanceOf(Collection::class, $commissions);
        $this->assertCount(5, $commissions);
        $this->assertInstanceOf(Commission::class, $commissions->first());
        $this->assertSame('8712626055150', $commissions->first()->ean);
        $this->assertSame('NEW', $commissions->first()->condition);
        $this->assertSame(34.99, $commissions->first()->unitPrice);
        $this->assertSame(0.99, $commissions->first()->fixedAmount);
        $this->assertSame(15, $commissions->first()->percentage);
        $this->assertSame(6.24, $commissions->first()->totalCost);

        $this->assertInstanceOf(Collection::class, $commissions->last()->reductions);
        $this->assertInstanceOf(Reduction::class, $commissions->last()->reductions->first());
        $this->assertSame(25.99, $commissions->last()->reductions->first()->maximumPrice);
        $this->assertSame(0.92, $commissions->last()->reductions->first()->costReduction);
        $this->assertInstanceOf(\DateTime::class, $commissions->last()->reductions->first()->startDate);
        $this->assertInstanceOf(\DateTime::class, $commissions->last()->reductions->first()->endDate);
    }

    /** @test */
    public function getCommissionByEan()
    {
        $this->useMock('200-get-commission-by-eancode-8712626055143.json');

        $ean = '8712626055143';
        $unitPrice = 24.50;
        $commission = $this->client->commission->get($ean, $unitPrice);

        $this->assertInstanceOf(Commission::class, $commission);
        $this->assertSame('8712626055143', $commission->ean);
        $this->assertSame('NEW', $commission->condition);
        $this->assertSame(0.24, $commission->unitPrice);
        $this->assertSame(0.99, $commission->fixedAmount);
        $this->assertSame(15, $commission->percentage);
        $this->assertSame(4.67, $commission->totalCost);
    }

    /** @test */
    public function getCommissionAndReductionByEan()
    {
        $this->useMock('200-get-commission-by-eancode-8718526069334.json');

        $ean = '8718526069334';
        $unitPrice = 25.85;
        $commission = $this->client->commission->get($ean, $unitPrice);
        $this->assertInstanceOf(Commission::class, $commission);
        $this->assertSame($ean, $commission->ean);
        $this->assertSame('NEW', $commission->condition);
        $this->assertSame(0.25, $commission->unitPrice);
        $this->assertSame(0.99, $commission->fixedAmount);
        $this->assertSame(15, $commission->percentage);
        $this->assertSame(3.82, $commission->totalCost);
        $this->assertSame(4.74, $commission->totalCostWithoutReduction);

        $this->assertInstanceOf(Collection::class, $commission->reductions);
        $this->assertInstanceOf(Reduction::class, $commission->reductions->first());
        $this->assertSame(25.99, $commission->reductions->first()->maximumPrice);
        $this->assertSame(0.92, $commission->reductions->first()->costReduction);
        $this->assertInstanceOf(\DateTime::class, $commission->reductions->first()->startDate);
        $this->assertInstanceOf(\DateTime::class, $commission->reductions->first()->endDate);
    }
}
