<?php

namespace Ascetik\Cacheable\Test;

use Ascetik\Cacheable\Callable\CacheableMethod;
use Ascetik\Cacheable\Test\Mocks\ControllerMock;
use Ascetik\Callapsule\Exceptions\MethodNotImplementedException;
use PHPUnit\Framework\TestCase;

class CacheableMethodTest extends TestCase
{
    private CacheableMethod $cacheable;
    private const STRING = 'test page';

    protected function setUp(): void
    {
        $this->cacheable = new CacheableMethod(new ControllerMock(self::STRING), 'action');
    }

    public function testShouldSerializeAnInstanceMethod()
    {
        $serial = serialize($this->cacheable);
        $this->assertIsString($serial);
    }

    public function testShouldUnserializeAnInstanceMethod()
    {
        $serial = serialize($this->cacheable);

        $deserial = unserialize($serial);
        [$subject, ] = $deserial->action();

        $this->assertInstanceOf(ControllerMock::class, $subject);
    }

    public function testShouldRunAnInstanceMethod()
    {
        $serial = serialize($this->cacheable);

        $deserial = unserialize($serial);
        [, $method] = $deserial->action();

        $this->assertSame('action', $method);
        $this->assertSame('page title : ' . self::STRING, $deserial->apply());
        $this->assertSame('page title : ' . self::STRING, $deserial());
    }

    public function testShouldThrowAnExceptionOnUnimplementedMethod()
    {
        $this->expectException(MethodNotImplementedException::class);
        new CacheableMethod(new \StdClass(), 'action');
    }
}
