<?php

namespace Mvdnbrk\MyParcel\Tests\Unit\Resources;

use Mvdnbrk\MyParcel\Exceptions\InvalidTimeException;
use Mvdnbrk\MyParcel\Resources\Time;
use Mvdnbrk\MyParcel\Tests\TestCase;

class TimeTest extends TestCase
{
    /** @test */
    public function constructing_a_valid_time()
    {
        $time = new Time('12:00:00');
        $this->assertEquals('12:00:00', $time->get());

        $time = new Time('12:00');
        $this->assertEquals('12:00:00', $time->get());
    }

    /** @test */
    public function constructing_an_invalid_time()
    {
        $this->expectException(InvalidTimeException::class);
        $time = new Time('not-valid');

        $this->expectException(InvalidTimeException::class);
        $time = new Time('99:00:00');
    }

    /** @test */
    public function can_get_a_time()
    {
        $time = new Time('12:34');
        $this->assertEquals('12:34:00', $time->get());
    }
}
