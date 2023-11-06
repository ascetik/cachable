<?php

namespace Ascetik\Cacheable\Test;

use Ascetik\Cacheable\Instanciable\ValueObjects\CacheableClosureProperty;
use PHPUnit\Framework\TestCase;

class CacheableCallablePropertyTest extends TestCase
{
    private CacheableClosureProperty $property;

    protected function setUp(): void
    {
        $this->property = new CacheableClosureProperty('fn', fn() => 'hello');
    }

    public function testShouldRegisterCorrectData()
    {
        $this->assertSame('string', $this->property->getType());
        $this->assertSame('name', $this->property->getName());
        $this->assertSame('John', $this->property->getValue());
       
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
        $this->assertInstanceOf(CacheableCustomProperty::class,$extract);
        $this->assertSame('name', $this->property->getName());
        $this->assertSame('John', $this->property->getValue());
    }

}
