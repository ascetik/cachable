<?php

namespace Ascetik\Cacheable\Test;

use Ascetik\Cacheable\Instanciable\ValueObjects\CacheableScalarProperty;
use PHPUnit\Framework\TestCase;

class CacheableScalarPropertyTest extends TestCase
{
    public function testShouldSerializeAString()
    {
        $property = new CacheableScalarProperty('name','John');
        echo $property->encode().PHP_EOL;
        $this->assertSame('string', $property->getType());
        // $serial = $property->encode();
        $expected = [
            'property' => 'name',
            'content'=>'John'
        ];
        // $this->assertSame(json_encode($expected), $property->encode());
    }
}
