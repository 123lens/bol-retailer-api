<?php

namespace Mvdnbrk\MyParcel\Tests\Unit\Resources;

use Mvdnbrk\MyParcel\Resources\ServicePoint;
use Mvdnbrk\MyParcel\Tests\TestCase;

class ServicePointTest extends TestCase
{
    /** @test */
    public function creating_a_valid_service_point_resource()
    {
        $servicepoint = new ServicePoint([
            'location' => 'Test Company B.V.',
            'street' => 'Poststraat',
            'number' => '1',
            'postal_code' => '1234AA',
            'city' => 'Amsterdam',
            'phone_number' => '0101111111',
            'distance' => '9999',
            'latitude' => '12.3456',
            'longitude' => '98.76543',
            'location_code' => '112233',
            'opening_hours' => [
                'monday' => ['08:00-18:30'],
                'tuesday' => ['08:00-18:30'],
                'wednesday' => ['08:00-18:30'],
                'thursday' => ['08:00-18:30'],
                'friday' => ['08:00-18:30'],
                'saturday' => ['08:00-17:00'],
                'sunday' => [],
            ],
        ]);

        $this->assertEquals('112233', $servicepoint->location_code);
        $this->assertEquals('Test Company B.V.', $servicepoint->name);
        $this->assertEquals('Poststraat', $servicepoint->street);
        $this->assertEquals('1', $servicepoint->number);
        $this->assertEquals('1234AA', $servicepoint->postal_code);
        $this->assertEquals('Amsterdam', $servicepoint->city);
        $this->assertEquals('0101111111', $servicepoint->phone_number);
        $this->assertEquals('9999', $servicepoint->distance);
        $this->assertEquals('12.3456', $servicepoint->latitude);
        $this->assertEquals('98.76543', $servicepoint->longitude);
        $this->assertIsArray($servicepoint->opening_hours);
    }

    /** @test */
    public function can_get_distance_for_humans()
    {
        $servicepoint = new ServicePoint;

        $servicepoint->distance = 999;
        $this->assertEquals('999 meter', $servicepoint->distanceForHumans());

        $servicepoint->distance = 1000;
        $this->assertEquals('1 km', $servicepoint->distanceForHumans());

        $servicepoint->distance = 1500;
        $this->assertEquals('1.5 km', $servicepoint->distanceForHumans());

        $servicepoint->distance = 2211;
        $this->assertEquals('2.2 km', $servicepoint->distanceForHumans());

        $servicepoint->distance = 2255;
        $this->assertEquals('2.3 km', $servicepoint->distanceForHumans());

        $servicepoint->distance = 11500;
        $this->assertEquals('12 km', $servicepoint->distanceForHumans());
    }

    /** @test */
    public function distance_for_humans_returns_empty_string_if_distance_is_null()
    {
        $servicepoint = new ServicePoint;

        $servicepoint->distance = null;

        $this->assertSame('', $servicepoint->distanceForHumans());
    }

    /** @test */
    public function location_may_be_used_as_an_alias_to_set_location_name()
    {
        $servicepoint = new ServicePoint([
            'location' => 'Test Location',
        ]);

        $this->assertEquals('Test Location', $servicepoint->name);
    }

    /** @test */
    public function location_may_be_used_as_an_alias_for_name()
    {
        $servicepoint = new ServicePoint([
            'location' => 'Test Location',
        ]);

        $this->assertEquals('Test Location', $servicepoint->name);
    }

    /** @test */
    public function location_code_may_be_used_as_an_alias_for_id()
    {
        $servicepoint = new ServicePoint([
            'location_code' => '112233',
        ]);

        $this->assertEquals('112233', $servicepoint->location_code);
    }

    /** @test */
    public function phone_number_may_be_used_as_an_alias_for_phone()
    {
        $servicepoint = new ServicePoint([
            'phone_number' => '0101111111',
        ]);

        $this->assertEquals('0101111111', $servicepoint->phone);
        $this->assertEquals('0101111111', $servicepoint->phone_number);
    }

    /** @test */
    public function latitude_and_longitude_are_converted_to_float()
    {
        $servicepoint = new ServicePoint([
            'latitude' => '1.11',
            'longitude' => '2.22',
        ]);

        $this->assertSame(1.11, $servicepoint->latitude);
        $this->assertSame(2.22, $servicepoint->longitude);
    }

    /** @test */
    public function to_array()
    {
        $servicepoint = new ServicePoint([
            'name' => 'Test name',
            'phone' => '0101111111',
            'location_code' => 'testcode1234',
            'opening_hours' => [
                'monday' => '9:00-17:00',
            ],
            'latitude' => 1.11,
            'longitude' => 2.22,
            'distance' => 100,
        ]);

        $array = $servicepoint->toArray();

        $this->assertIsArray($array);
        $this->assertEquals('testcode1234', $array['location_code']);
        $this->assertEquals('Test name', $array['name']);
        $this->assertEquals('0101111111', $array['phone']);
        $this->assertEquals(['monday' => '9:00-17:00'], $array['opening_hours']);
        $this->assertEquals(1.11, $array['latitude']);
        $this->assertEquals(2.22, $array['longitude']);
        $this->assertEquals('100 meter', $array['distance']);
    }

    /** @test */
    public function to_array_removes_empty_attributes()
    {
        $servicepoint = new ServicePoint([
            'id' => null,
            'name' => 'Test name',
            'phone' => null,
            'opening_hours' => [],
            'latitude' => null,
            'longitude' => null,
            'distance' => null,
        ]);

        $array = $servicepoint->toArray();

        $this->assertIsArray($array);
        $this->assertArrayNotHasKey('id', $array);
        $this->assertEquals('Test name', $array['name']);
        $this->assertArrayNotHasKey('phone', $array);
        $this->assertArrayNotHasKey('latitude', $array);
        $this->assertArrayNotHasKey('longitude', $array);
        $this->assertArrayNotHasKey('distance', $array);
        $this->assertArrayNotHasKey('opening_hours', $array);
    }

    /** @test */
    public function number_should_be_casted_to_a_string()
    {
        $servicepoint = new ServicePoint([
            'number' => 999,
        ]);

        $array = $servicepoint->toArray();

        $this->assertIsString($array['number']);
        $this->assertEquals('999', $array['number']);
    }
}
