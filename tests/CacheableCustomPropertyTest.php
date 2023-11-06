<?php

namespace Ascetik\Cacheable\Test;

use Ascetik\Cacheable\Instanciable\ValueObjects\CacheableCustomProperty;
use PHPUnit\Framework\TestCase;

class CacheableCustomPropertyTest extends TestCase
{
    public function testShouldRegisterCorrectData()
    {
        $property = new CacheableCustomProperty('name','John');
        $this->assertSame('string', $property->getType());
        $this->assertSame('name', $property->getName());
        $this->assertSame('John', $property->getValue());
       
    }
    public function testShouldSerializeAString()
    {
        $property = new CacheableCustomProperty('name','John');
        $serial = serialize($property);
        $this->assertIsString($serial);
        // $serial = $property->encode();
        // $expected = [
        //     'property' => 'name',
        //     'content'=>'John'
        // ];
        // $this->assertSame(json_encode($expected), $property->encode());
    }

    public function testShouldBeAbleToDeserializeAString()
    {
        $property = new CacheableCustomProperty('name','John');
        $serial = serialize($property);
        echo $serial.PHP_EOL;
        $extract = unserialize($serial);
        $this->assertInstanceOf(CacheableCustomProperty::class,$extract);
        $this->assertSame('name', $property->getName());
        $this->assertSame('John', $property->getValue());
    }
}
