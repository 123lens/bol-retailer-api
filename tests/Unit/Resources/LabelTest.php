<?php

namespace Mvdnbrk\MyParcel\Tests\Unit\Resources;

use Mvdnbrk\MyParcel\Resources\Label;
use Mvdnbrk\MyParcel\Tests\TestCase;
use Mvdnbrk\MyParcel\Types\LabelPositions;
use Mvdnbrk\MyParcel\Types\PaperSize;

class LabelTest extends TestCase
{
    /** @test */
    public function create_a_new_label()
    {
        $label = new Label();

        $this->assertSame(PaperSize::A6, $label->format);
        $this->assertNull($label->positions);

        $label = new Label([
            'format' => PaperSize::A4,
            'positions' => LabelPositions::BOTTOM_RIGHT,
        ]);

        $this->assertSame(PaperSize::A4, $label->format);
        $this->assertSame(LabelPositions::BOTTOM_RIGHT, $label->positions);
    }

    /** @test */
    public function creating_a_new_label_with_a4_format_sets_the_position_to_top_left_as_a_default()
    {
        $label = new Label([
            'format' => PaperSize::A4,
        ]);

        $this->assertSame(PaperSize::A4, $label->format);
        $this->assertSame(LabelPositions::TOP_LEFT, $label->positions);
    }

    /** @test */
    public function setting_the_paper_size_to_a6_resets_the_position_to_null()
    {
        $label = new Label([
            'format' => PaperSize::A4,
            'positions' => LabelPositions::BOTTOM_RIGHT,
        ]);

        $label->format = PaperSize::A6;

        $this->assertSame(PaperSize::A6, $label->format);
        $this->assertNull($label->positions);
    }

    /** @test */
    public function position_may_be_used_as_an_alias_to_positions()
    {
        $label = new Label([
            'format' => PaperSize::A4,
            'position' => LabelPositions::BOTTOM_RIGHT,
        ]);

        $this->assertSame(PaperSize::A4, $label->format);
        $this->assertSame(LabelPositions::BOTTOM_RIGHT, $label->positions);
    }

    /** @test */
    public function size_may_be_used_as_an_alias_to_format()
    {
        $label = new Label([
            'size' => PaperSize::A4,
        ]);

        $this->assertSame(PaperSize::A4, $label->format);
    }

    /** @test */
    public function to_array()
    {
        $label = new Label();

        $array = $label->toArray();

        $this->assertIsArray($array);
        $this->assertSame(PaperSize::A6, $array['format']);
        $this->assertArrayNotHasKey('positions', $array);

        $label = new Label([
            'format' => PaperSize::A4,
            'position' => LabelPositions::BOTTOM_RIGHT,
        ]);

        $array = $label->toArray();

        $this->assertIsArray($array);
        $this->assertSame(PaperSize::A4, $array['format']);
        $this->assertSame(LabelPositions::BOTTOM_RIGHT, $array['positions']);
    }
}
