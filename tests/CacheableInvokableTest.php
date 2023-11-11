<?php

namespace Ascetik\Cacheable\Test;

use Ascetik\Cacheable\Callable\CacheableInvokable;
use Ascetik\Cacheable\Test\Mocks\InvokableMock;
use Ascetik\Callapsule\Exceptions\UninvokableClassException;
use PHPUnit\Framework\TestCase;

class CacheableInvokableTest extends TestCase
{
    private CacheableInvokable $cacheable;

    protected function setUp(): void
    {
        $subject = new InvokableMock();
        $this->cacheable = new CacheableInvokable($subject);
    }
    public function testShouldBeAbleToHandleAnInvokableObject()
    {
        $serial = serialize($this->cacheable);
        $this->assertIsString($serial);
    }

    public function testShouldFindInvokableBackAfterDeserialization()
    {
        $serial = serialize($this->cacheable);
        $deserial = unserialize($serial);
        $this->assertInstanceOf(InvokableMock::class, $deserial->callable());
        $this->assertSame('Hello John', $deserial->run(['John']));
    }

    public function testShouldReturnWrappedInstanceExpectedResult()
    {
        $serial = serialize($this->cacheable);
        $deserial = unserialize($serial);
        $this->assertSame('Hello John', $deserial->run(['John']));
    }

    public function testShouldThrownExceptionOnUninvokableInstance()
    {
        $this->expectException(UninvokableClassException::class);
        new CacheableInvokable(new \StdClass());
    }
}
