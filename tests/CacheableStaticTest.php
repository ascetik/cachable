<?php

namespace Ascetik\Cacheable\Test;

use Ascetik\Cacheable\Callable\CacheableStatic;
use Ascetik\Cacheable\Test\Mocks\FactoryMock;
use Ascetik\Callapsule\Exceptions\ClassNotFoundException;
use Ascetik\Callapsule\Exceptions\MethodNotImplementedException;
use PHPUnit\Framework\TestCase;

class CacheableStaticTest extends TestCase
{
    private CacheableStatic $cacheable;

    protected function setUp(): void
    {
        $this->cacheable = new CacheableStatic(FactoryMock::class, 'create');
    }

    public function testShouldSerializeAStaticMethod()
    {
        $serial = serialize($this->cacheable);
        $this->assertIsString($serial);
    }

    public function testShouldUnserializeAStaticMethod()
    {
        $serial = serialize($this->cacheable);
        /** @var CacheableStatic $extract */
        $extract = unserialize($serial);
        $this->assertInstanceOf(CacheableStatic::class, $extract);
    }

    public function testShouldRunStaticMethod()
    {
        $serial = serialize($this->cacheable);
        /** @var CacheableStatic $extract */
        $extract = unserialize($serial);
        [$subject, $method] = $extract->action();
        $this->assertSame(FactoryMock::class, $subject);
        $this->assertSame('create', $method);
        $this->assertSame('new Mock created for serialize tests', $extract(['serialize tests']));
    }

    public function testShouldThrowAnExceptionOnNotFoundClass()
    {
        $this->expectException(ClassNotFoundException::class);
        new CacheableStatic('Foo', 'bar');
    }

    public function testShouldThrowAnExceptionOnNotNotImplementedMethod()
    {
        $this->expectException(MethodNotImplementedException::class);
        new CacheableStatic(\StdClass::class, 'bar');
    }
}
