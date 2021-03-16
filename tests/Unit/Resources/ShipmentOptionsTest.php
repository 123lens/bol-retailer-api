<?php

namespace Mvdnbrk\MyParcel\Tests\Unit\Resources;

use Mvdnbrk\MyParcel\Resources\ShipmentOptions;
use Mvdnbrk\MyParcel\Tests\TestCase;

class ShipmentOptionsTest extends TestCase
{
    /** @test */
    public function creating_a_shipments_options_resource()
    {
        $options = new ShipmentOptions([
            'label_description' => 'Test description',
        ]);

        $this->assertEquals('Test description', $options->label_description);
    }

    /** @test */
    public function description_may_be_used_as_an_alias_to_label_description()
    {
        $recipient = new ShipmentOptions([
            'description' => 'Test description',
        ]);

        $this->assertEquals('Test description', $recipient->label_description);
        $this->assertEquals('Test description', $recipient->description);
    }

    /** @test */
    public function to_array()
    {
        $options = new ShipmentOptions();

        $array = $options->toArray();

        $this->assertIsArray($array);
        $this->assertSame(1, $array['package_type']);
        $this->assertSame(0, $array['return']);
        $this->assertSame(0, $array['signature']);
        $this->assertSame(0, $array['large_format']);
        $this->assertSame(0, $array['only_recipient']);
    }

    /** @test */
    public function to_array_converts_booleans_to_an_integer_value()
    {
        $options = new ShipmentOptions([
            'return' => true,
            'signature' => true,
            'large_format' => false,
            'only_recipient' => false,
        ]);

        $this->assertSame(true, $options->return);
        $this->assertSame(true, $options->signature);
        $this->assertSame(false, $options->large_format);
        $this->assertSame(false, $options->only_recipient);

        $array = $options->toArray();

        $this->assertSame(1, $array['return']);
        $this->assertSame(1, $array['signature']);
        $this->assertSame(0, $array['large_format']);
        $this->assertSame(0, $array['only_recipient']);
    }
}
