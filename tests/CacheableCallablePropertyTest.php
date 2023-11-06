<?php

declare(strict_types=1);

namespace Ascetik\Cacheable\Test;

use Ascetik\Cacheable\Instanciable\ValueObjects\CacheableCallableProperty;
use Closure;
use PHPUnit\Framework\TestCase;

class CacheableCallablePropertyTest extends TestCase
{
    private CacheableCallableProperty $property;

    protected function setUp(): void
    {
        $this->property = new CacheableCallableProperty('fn', fn () => 'hello');
    }

    public function testShouldRegisterCorrectData()
    {
        $this->assertSame('fn', $this->property->getName());
        $this->assertInstanceOf(Closure::class, $this->property->getValue());
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
        $this->assertInstanceOf(CacheableCallableProperty::class, $extract);
        $this->assertSame('fn', $this->property->getName());
        $func = $this->property->getValue();
        $this->assertInstanceOf(Closure::class, $func);
        $result = call_user_func($func);
        $this->assertSame('hello', $result);
    }
}
