<?php
namespace Budgetlens\BolRetailerApi\Tests\Feature\Endpoints;

use Budgetlens\BolRetailerApi\Resources\Inventory;
use Budgetlens\BolRetailerApi\Tests\TestCase;
use Illuminate\Support\Collection;

class InventoryTest extends TestCase
{
    /** @test */
    public function getInventory()
    {
        $this->useMock('200-get-inventory.json');

        $inventory = $this->client->inventory->get();

        $this->assertInstanceOf(Collection::class, $inventory);
        $this->assertCount(1, $inventory);
        $this->assertInstanceOf(Inventory::class, $inventory->first());
        $this->assertNotNull($inventory->first()->ean);
        $this->assertNotNull($inventory->first()->bsku);
        $this->assertSame(0, $inventory->first()->gradedStock);
        $this->assertSame(5, $inventory->first()->regularStock);
    }
}
