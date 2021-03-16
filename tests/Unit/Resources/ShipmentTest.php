<?php

namespace Mvdnbrk\MyParcel\Tests\Unit\Resources;

use Mvdnbrk\MyParcel\Resources\Shipment;
use Mvdnbrk\MyParcel\Tests\TestCase;
use Mvdnbrk\MyParcel\Types\ShipmentStatus;

class ShipmentTest extends TestCase
{
    /** @test */
    public function creating_a_shipment_resource()
    {
        $shipment = new Shipment([
            'id' => 123456,
            'barcode' => '3SMYPA123456789',
            'created' => '2018-01-01 12:34:56',
            'status' => ShipmentStatus::CONCEPT,
        ]);

        $this->assertEquals(123456, $shipment->id);
        $this->assertEquals('3SMYPA123456789', $shipment->barcode);
        $this->assertEquals('2018-01-01 12:34:56', $shipment->created);
        $this->assertEquals(ShipmentStatus::CONCEPT, $shipment->status);
    }

    /** @test */
    public function to_array()
    {
        $shipment = new Shipment([
            'id' => 123456,
            'barcode' => '3SMYPA123456789',
            'created' => '2018-01-01 12:34:56',
            'status' => ShipmentStatus::CONCEPT,
        ]);

        $array = $shipment->toArray();

        $this->assertIsArray($array);
        $this->assertEquals(123456, $array['id']);
        $this->assertEquals('3SMYPA123456789', $array['barcode']);
        $this->assertEquals('2018-01-01 12:34:56', $array['created']);
        $this->assertEquals(ShipmentStatus::CONCEPT, $array['status']);
    }
}
