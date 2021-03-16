<?php

namespace Mvdnbrk\MyParcel\Tests\Unit\Resources;

use Mvdnbrk\MyParcel\Resources\Recipient;
use Mvdnbrk\MyParcel\Tests\TestCase;

class RecipientTest extends TestCase
{
    private function validParams($overrides = [])
    {
        return array_merge([
            'company' => 'Test Company B.V.',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '0101111111',
            'street' => 'Poststraat',
            'number' => '1',
            'number_suffix' => 'A',
            'postal_code' => '1234AA',
            'city' => 'Amsterdam',
            'region' => 'Noord-Holland',
            'cc' => 'NL',
        ], $overrides);
    }

    /** @test */
    public function creating_a_valid_recipient_resource()
    {
        $recipient = new Recipient([
            'company' => 'Test Company B.V.',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '0101111111',
            'street' => 'Poststraat',
            'street_additional_info' => 'Test info',
            'number' => '1',
            'number_suffix' => 'A',
            'postal_code' => '1234AA',
            'city' => 'Amsterdam',
            'region' => 'Noord-Holland',
            'cc' => 'NL',
        ]);

        $this->assertEquals('Test Company B.V.', $recipient->company);
        $this->assertEquals('John', $recipient->first_name);
        $this->assertEquals('Doe', $recipient->last_name);
        $this->assertEquals('john@example.com', $recipient->email);
        $this->assertEquals('0101111111', $recipient->phone);
        $this->assertEquals('Poststraat', $recipient->street);
        $this->assertEquals('Test info', $recipient->street_additional_info);
        $this->assertEquals('1', $recipient->number);
        $this->assertEquals('A', $recipient->number_suffix);
        $this->assertEquals('1234AA', $recipient->postal_code);
        $this->assertEquals('Amsterdam', $recipient->city);
        $this->assertEquals('Noord-Holland', $recipient->region);
        $this->assertEquals('NL', $recipient->cc);
    }

    /** @test */
    public function it_can_retrieve_the_full_name()
    {
        $recipient = new Recipient([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $this->assertEquals('John Doe', $recipient->full_name);
    }

    /** @test */
    public function full_name_gets_trimmed_when_first_or_last_name_is_abscent()
    {
        $recipient = new Recipient([
            'first_name' => '',
            'last_name' => 'Doe',
        ]);

        $this->assertEquals('Doe', $recipient->full_name);

        $recipient = new Recipient([
            'first_name' => 'John',
            'last_name' => '',
        ]);

        $this->assertEquals('John', $recipient->full_name);
    }

    /** @test */
    public function country_code_may_be_used_as_an_alias_to_cc()
    {
        $recipient = new Recipient([
            'country_code' => 'NL',
        ]);

        $this->assertEquals('NL', $recipient->cc);
    }

    /** @test */
    public function zipcode_may_be_used_as_an_alias_to_postal_code()
    {
        $recipient = new Recipient([
            'zipcode' => '9999ZZ',
        ]);

        $this->assertEquals('9999ZZ', $recipient->postal_code);
    }

    /** @test */
    public function lower_case_country_code_is_converted_to_uppercase()
    {
        $recipient = new Recipient($this->validParams([
            'cc' => 'nl',
        ]));

        $this->assertEquals('NL', $recipient->cc);
    }

    /** @test */
    public function lower_case_postal_code_is_converted_to_uppercase()
    {
        $recipient = new Recipient($this->validParams([
            'postal_code' => '1234aa',
        ]));

        $this->assertEquals('1234AA', $recipient->postal_code);
    }

    /** @test */
    public function number_should_be_casted_to_a_string()
    {
        $recipient = new Recipient($this->validParams([
            'number' => 999,
        ]));

        $array = $recipient->toArray();

        $this->assertIsString($array['number']);
        $this->assertEquals('999', $array['number']);
    }

    /** @test */
    public function to_array()
    {
        $attributes = [
            'company' => 'Test Company B.V.',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '0101111111',
            'street' => 'Poststraat',
            'number' => '1',
            'number_suffix' => 'A',
            'postal_code' => '1234AA',
            'city' => 'Amsterdam',
            'region' => 'Noord-Holland',
            'cc' => 'NL',
        ];

        $recipient = new Recipient($attributes);

        $array = $recipient->toArray();

        $this->assertIsArray($array);

        $this->assertArrayNotHasKey('first_name', $array);
        $this->assertArrayNotHasKey('last_name', $array);

        $this->assertArrayHasKey('person', $array);
        $this->assertEquals('John Doe', $array['person']);
    }
}
