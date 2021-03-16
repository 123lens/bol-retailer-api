<?php

namespace Mvdnbrk\MyParcel\Tests\Unit\Resources;

use Mvdnbrk\MyParcel\Resources\Parcel;
use Mvdnbrk\MyParcel\Resources\Recipient;
use Mvdnbrk\MyParcel\Resources\ServicePoint;
use Mvdnbrk\MyParcel\Tests\TestCase;
use Mvdnbrk\MyParcel\Types\DeliveryType;
use Mvdnbrk\MyParcel\Types\PackageType;

class ParcelTest extends TestCase
{
    /** @test */
    public function create_a_new_parcel()
    {
        $parcel = new Parcel([
            'reference_identifier' => 'test-123',
            'recipient' => [
                'company' => 'Test Company B.V.',
                'first_name' => 'John',
                'last_name' => 'Doe',
            ],
            'options' => [
                'description' => 'Test label description',
                'signature' => 1,
            ],
        ]);

        $this->assertEquals('test-123', $parcel->reference_identifier);
        $this->assertEquals('Test Company B.V.', $parcel->recipient->company);
        $this->assertEquals('John', $parcel->recipient->first_name);
        $this->assertEquals('Doe', $parcel->recipient->last_name);
        $this->assertEquals('Test label description', $parcel->options->label_description);
        $this->assertSame(1, $parcel->options->signature);
    }

    /** @test */
    public function it_can_set_the_recipient_by_passing_a_recipient_object()
    {
        $recipient = new Recipient([
            'company' => 'Test Company B.V.',
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $parcel = new Parcel([
            'recipient' => $recipient,
        ]);

        $this->assertEquals('Test Company B.V.', $parcel->recipient->company);
        $this->assertEquals('John', $parcel->recipient->first_name);
        $this->assertEquals('Doe', $parcel->recipient->last_name);
    }

    /** @test */
    public function it_can_set_a_label_description()
    {
        $parcel = new Parcel();

        $this->assertNull($parcel->options->label_description);

        $parcel->labelDescription('Test label description');
        $this->assertEquals('Test label description', $parcel->options->label_description);

        $parcel->labelDescription('   Test   ');
        $this->assertEquals('Test', $parcel->options->label_description);
    }

    /** @test */
    public function calling_the_label_description_method_returns_the_same_parcel_instance()
    {
        $parcel = new Parcel();

        $this->assertSame($parcel, $parcel->labelDescription('test'));
    }

    /** @test */
    public function it_can_require_a_signature_from_the_recipient_of_the_parcel()
    {
        $parcel = new Parcel();
        $this->assertFalse($parcel->options->signature);

        $parcel->signature();

        $this->assertTrue($parcel->options->signature);
    }

    /** @test */
    public function calling_the_signature_method_returns_the_same_parcel_instance()
    {
        $parcel = new Parcel();

        $this->assertSame($parcel, $parcel->signature());
    }

    /** @test */
    public function it_can_require_an_age_check_from_the_recipient_of_the_parcel()
    {
        $parcel = new Parcel();
        $this->assertFalse($parcel->options->age_check);

        $parcel->ageCheck();

        $this->assertTrue($parcel->options->age_check);
    }

    /** @test */
    public function calling_the_agecheck_method_returns_the_same_parcel_instance()
    {
        $parcel = new Parcel();

        $this->assertSame($parcel, $parcel->ageCheck());
    }

    /** @test */
    public function it_can_set_a_parcel_as_a_mailbox_package()
    {
        $parcel = new Parcel([
            'options' => [
                'signature' => true,
                'large_format' => true,
                'only_recipent' => true,
            ],
        ]);

        $this->assertTrue($parcel->options->signature);

        $parcel->mailboxpackage();

        $this->assertInstanceOf(Parcel::class, $parcel);
        $this->assertSame(PackageType::MAILBOX_PACKAGE, $parcel->options->package_type);
        $this->assertFalse($parcel->options->signature);
        $this->assertFalse($parcel->options->large_format);
        $this->assertFalse($parcel->options->only_recipient);
    }

    /** @test */
    public function calling_the_mailboxpackage_method_returns_the_same_parcel_instance()
    {
        $parcel = new Parcel();

        $this->assertSame($parcel, $parcel->mailboxpackage());
    }

    /** @test */
    public function it_can_set_a_parcel_to_be_only_delivered_to_the_recipient()
    {
        $parcel = new Parcel();

        $this->assertFalse($parcel->options->only_recipient);

        $parcel->onlyRecipient();

        $this->assertInstanceOf(Parcel::class, $parcel);
        $this->assertTrue($parcel->options->only_recipient);
    }

    /** @test */
    public function calling_the_only_recipient_method_returns_the_same_parcel_instance()
    {
        $parcel = new Parcel();

        $this->assertSame($parcel, $parcel->onlyRecipient());
    }

    /** @test */
    public function it_can_set_a_parcel_to_be_returned_to_sender_when_recipient_is_not_at_home()
    {
        $parcel = new Parcel();

        $this->assertFalse($parcel->options->return);

        $parcel->returnToSender();

        $this->assertInstanceOf(Parcel::class, $parcel);
        $this->assertTrue($parcel->options->return);
    }

    /** @test */
    public function calling_the_return_to_sender_method_returns_the_same_parcel_instance()
    {
        $parcel = new Parcel();

        $this->assertSame($parcel, $parcel->returnToSender());
    }

    /** @test */
    public function it_can_set_a_pickup_location_where_the_recipient_can_pick_up_the_parcel_with_an_array()
    {
        $parcel = new Parcel();

        $this->assertNull($parcel->pickup);
        $this->assertArrayNotHasKey('pickup', $parcel->toArray());

        $parcel->fill([
            'pickup' => [
                'name' => 'MyParcel',
                'street' => 'Siriusdreef',
                'number' => '66',
                'postal_code' => '2132WT',
                'city' => 'Hoofddorp',
                'cc' => 'NL',
            ],
        ]);

        $this->assertInstanceOf(Parcel::class, $parcel);
        $this->assertInstanceOf(ServicePoint::class, $parcel->pickup);
        $this->assertArrayHasKey('pickup', $parcel->toArray());

        $this->assertEquals('MyParcel', $parcel->pickup->name);
        $this->assertEquals('Siriusdreef', $parcel->pickup->street);
        $this->assertEquals('66', $parcel->pickup->number);
        $this->assertEquals('2132WT', $parcel->pickup->postal_code);
        $this->assertEquals('Hoofddorp', $parcel->pickup->city);
        $this->assertEquals('NL', $parcel->pickup->cc);
        $this->assertEquals(PackageType::PACKAGE, $parcel->options->package_type);
        $this->assertEquals(DeliveryType::PICKUP, $parcel->options->delivery_type);
        $this->assertFalse($parcel->options->only_recipient);
        $this->assertTrue($parcel->options->signature);
    }

    /** @test */
    public function it_can_set_a_pickup_location_after_the_parcel_is_created()
    {
        $parcel = new Parcel();

        $this->assertNull($parcel->pickup);
        $this->assertArrayNotHasKey('pickup', $parcel->toArray());

        $parcel->pickup = [
            'name' => 'MyParcel',
            'street' => 'Siriusdreef',
            'number' => '66',
            'postal_code' => '2132WT',
            'city' => 'Hoofddorp',
            'cc' => 'NL',
        ];

        $this->assertInstanceOf(Parcel::class, $parcel);
        $this->assertInstanceOf(ServicePoint::class, $parcel->pickup);
        $this->assertArrayHasKey('pickup', $parcel->toArray());

        $this->assertEquals('MyParcel', $parcel->pickup->name);
        $this->assertEquals('Siriusdreef', $parcel->pickup->street);
        $this->assertEquals('66', $parcel->pickup->number);
        $this->assertEquals('2132WT', $parcel->pickup->postal_code);
        $this->assertEquals('Hoofddorp', $parcel->pickup->city);
        $this->assertEquals('NL', $parcel->pickup->cc);
        $this->assertEquals(PackageType::PACKAGE, $parcel->options->package_type);
        $this->assertEquals(DeliveryType::PICKUP, $parcel->options->delivery_type);
        $this->assertFalse($parcel->options->only_recipient);
        $this->assertTrue($parcel->options->signature);
    }

    /** @test */
    public function reference_may_be_used_as_an_alias_to_reference_identifier()
    {
        $parcel = new Parcel([
            'reference' => 'test-123',
        ]);

        $this->assertEquals('test-123', $parcel->reference_identifier);
        $this->assertEquals('test-123', $parcel->reference);
    }

    /** @test */
    public function to_array()
    {
        $attributes = [
            'carrier' => 1,
            'reference_identifier' => 'test-123',
            'options' => [
                'age_check' => 0,
                'delivery_type' => 2,
                'label_description' => 'Test label description',
                'large_format' => 0,
                'only_recipient' => 0,
                'package_type' => 1,
                'return' => 0,
                'signature' => 1,
            ],
            'recipient' => [
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
            ],
        ];

        $parcel = new Parcel($attributes);

        $array = $parcel->toArray();

        $this->assertIsArray($array);
        $this->assertSame(1, $array['carrier']);
        $this->assertSame('test-123', $array['reference_identifier']);

        $this->assertArrayHasKey('options', $array);
        $this->assertSame(2, $array['options']['delivery_type']);
        $this->assertSame('Test label description', $array['options']['label_description']);
        $this->assertSame(0, $array['options']['age_check']);
        $this->assertSame(0, $array['options']['large_format']);
        $this->assertSame(0, $array['options']['only_recipient']);
        $this->assertSame(1, $array['options']['package_type']);
        $this->assertSame(0, $array['options']['return']);
        $this->assertSame(1, $array['options']['signature']);

        $this->assertArrayHasKey('recipient', $array);
        $this->assertSame('Test Company B.V.', $array['recipient']['company']);
        $this->assertSame('John Doe', $array['recipient']['person']);
        $this->assertSame('john@example.com', $array['recipient']['email']);
        $this->assertSame('0101111111', $array['recipient']['phone']);
        $this->assertSame('Poststraat', $array['recipient']['street']);
        $this->assertSame('1', $array['recipient']['number']);
        $this->assertSame('A', $array['recipient']['number_suffix']);
        $this->assertSame('1234AA', $array['recipient']['postal_code']);
        $this->assertSame('Amsterdam', $array['recipient']['city']);
        $this->assertSame('Noord-Holland', $array['recipient']['region']);
        $this->assertSame('NL', $array['recipient']['cc']);
    }

    /** @test */
    public function to_array_with_pickup()
    {
        $attributes = [
            'pickup' => [
                'name' => 'Test Company B.V.',
            ],
        ];

        $parcel = new Parcel($attributes);

        $array = $parcel->toArray();

        $this->assertSame('Test Company B.V.', $array['pickup']['location_name']);
    }

    /** @test */
    public function reference_identifier_is_optional()
    {
        $parcel = new Parcel([
            'reference_identifier' => null,
        ]);

        $this->assertArrayNotHasKey('reference_identifier', $parcel->toArray());
    }

    /** @test */
    public function options_are_required()
    {
        $parcel = new Parcel();

        $array = $parcel->toArray();

        $this->assertArrayHasKey('options', $array);
        $this->assertIsArray($array['options']);
    }

    /** @test */
    public function pickup_is_optional()
    {
        $parcel = new Parcel();

        $array = $parcel->toArray();

        $this->assertArrayNotHasKey('pickup', $array);
    }

    /** @test */
    public function recipient_is_required()
    {
        $parcel = new Parcel();

        $array = $parcel->toArray();

        $this->assertArrayHasKey('recipient', $array);
        $this->assertIsArray($array['recipient']);
    }

    /** @test */
    public function passing_null_value_to_set_pickup_attribute_should_return_null()
    {
        $parcel = new Parcel();

        $this->assertNull($parcel->setPickupAttribute(null));
    }

    /** @test */
    public function passing_null_value_to_set_pickup_attribute_should_set_pickup_to_null()
    {
        $parcel = new Parcel([
            'pickup' => [
                'name' => 'MyParcel',
            ],
        ]);

        $this->assertEquals('MyParcel', $parcel->pickup->name);

        $parcel->setPickupAttribute(null);

        $this->assertNull($parcel->pickup);
    }

    /** @test */
    public function reference_identifier_should_be_casted_to_a_string()
    {
        $parcel = new Parcel([
            'reference_identifier' => 999999,
        ]);

        $array = $parcel->toArray();

        $this->assertIsString($array['reference_identifier']);
        $this->assertEquals('999999', $array['reference_identifier']);
    }
}
