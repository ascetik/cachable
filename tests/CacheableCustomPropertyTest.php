<?php

declare(strict_types=1);

namespace Ascetik\Cacheable\Test;

use Ascetik\Cacheable\Instanciable\ValueObjects\CacheableCustomProperty;
use PHPUnit\Framework\TestCase;

class CacheableCustomPropertyTest extends TestCase
{
    private CacheableCustomProperty $property;

    protected function setUp(): void
    {
        $this->property = new CacheableCustomProperty('name','John');
        
    }
    public function testShouldRegisterCorrectData()
    {
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
