<?php

namespace Ascetik\Cacheable\Test;

use Ascetik\Cacheable\Instanciable\ValueObjects\CacheableObjectProperty;
use Ascetik\Cacheable\Test\Mocks\InvokablePropertyMock;
use PHPUnit\Framework\TestCase;

class CacheableObjectPropertyTest extends TestCase
{
    private CacheableObjectProperty $property;

    protected function setUp(): void
    {
        $mock = new InvokablePropertyMock(fn (string $title) => 'page for ' . $title);
        $this->property = new CacheableObjectProperty('testing', $mock);
    }

    public function testShouldRegisterCorrectData()
    {
        $this->assertSame('testing', $this->property->getName());
        $this->assertInstanceOf(InvokablePropertyMock::class, $this->property->getValue());
    }

    public function testShouldSerializeAString()
    {
        $serial = serialize($this->property);
        $this->assertIsString($serial);
    }

    public function testShouldBeAbleToDeserializeAString()
    {
        $serial = serialize($this->property);
        $extract = unserialize($serial);
        $this->assertTrue(true);
        $this->assertInstanceOf(CacheableObjectProperty::class, $extract);
        $mock = $this->property->getValue();
        $this->assertInstanceOf(InvokablePropertyMock::class, $mock);
        $this->assertSame('testing', $this->property->getName());
        $result = call_user_func($mock,'test');
        $this->assertSame('page for test', $result);
    }
}
