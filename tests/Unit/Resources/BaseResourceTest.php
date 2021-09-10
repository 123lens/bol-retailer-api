<?php
namespace Budgetlens\BolRetailerApi\Tests\Unit\Resources;

use Budgetlens\BolRetailerApi\Exceptions\JsonEncodingException;
use Budgetlens\BolRetailerApi\Resources\BaseResource;
use Budgetlens\BolRetailerApi\Tests\TestCase;

class BaseResourceTest extends TestCase
{
    /** @test */
    public function createNewResourceWithArray()
    {
        $resource = new ResourceStub([
            'foo' => 'bar',
        ]);

        $this->assertEquals('bar', $resource->foo);
    }

    /** @test */
    public function setAttribute()
    {
        $resource = new ResourceStub();
        $resource->setAttribute('foo', 'bar');

        $this->assertEquals('bar', $resource->foo);
    }

    /** @test */
    public function setMutator()
    {
        $resource = new ResourceStub();

        $this->assertTrue($resource->hasSetMutator('testMutator'));

        $resource->setAttribute('test_mutator', 'bar');

        $this->assertEquals('bar', $resource->foo);

        $resource->setAttribute('testMutator', 'qux');

        $this->assertEquals('qux', $resource->foo);
    }

    /** @test */
    public function getMutator()
    {
        $resource = new ResourceStub();

        $this->assertNull($resource->getAttribute(null));
        $this->assertNull($resource->getAttribute('not-existent'));
        $this->assertNull($resource->getAttributeValue('non-existent-key'));

        $this->assertEquals('test-get-mutator-value', $resource->testGetter);
    }

    /** @test */
    public function getAttributeValue()
    {
        $resource = new ResourceStub();
        $resource->foo = 'test';

        $this->assertEquals('test', $resource->getAttributeValue('foo'));
    }

    /** @test */
    public function toArrayTest()
    {
        $resource = new ResourceStub([
            'foo' => 'bar',
        ]);

        $array = $resource->toArray();

        $this->assertIsArray($array);
        $this->assertEquals('bar', $array['foo']);
    }

    /** @test */
    public function toArrayRemovesAttributesWithNullValues()
    {
        $resource = new ResourceStub([
            'foo' => null,
        ]);

        $array = $resource->toArray();

        $this->assertSame([], $array);
    }

    /** @test */
    public function toJsonTest()
    {
        $resource = new ResourceStub([
            'foo' => 'bar',
        ]);

        $this->assertJsonStringEqualsJsonString(json_encode(['foo' => 'bar']), $resource->toJson());
    }

    /** @test */
    public function encodingMalformedJsonThrowsAnException()
    {
        $this->expectException(JsonEncodingException::class);
        $this->expectExceptionMessage(
            "Error encoding resource [Budgetlens\BolRetailerApi\Tests\Unit\Resources\ResourceStub] to JSON: " .
            "Malformed UTF-8 characters, possibly incorrectly encoded."
        );

        $obj = new \stdClass;
        $obj->foo = "b\xF8r";

        $resource = new ResourceStub([
            'foo' => $obj,
        ]);

        $resource->toJson();
    }
}

class ResourceStub extends BaseResource
{
    public $foo;

    public function setTestMutatorAttribute($value)
    {
        $this->foo = $value;
    }

    public function getTestGetterAttribute()
    {
        return 'test-get-mutator-value';
    }
}
