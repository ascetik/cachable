<?php

declare(strict_types=1);

namespace Ascetik\Cacheable\Test;

use Ascetik\Cacheable\Callable\CacheableClosure;
use Ascetik\Cacheable\Callable\CacheableInvokable;
use Ascetik\Cacheable\Callable\CacheableMethod;
use Ascetik\Cacheable\Test\Mocks\ControllerMock;
use Ascetik\Cacheable\Test\Mocks\FactoryMock;
use Ascetik\Cacheable\Test\Mocks\InvokableMock;
use Ascetik\Cacheable\Types\CacheableCall;
use PHPUnit\Framework\TestCase;

class CacheableCallsTest extends TestCase
{
    public function testShouldSerializeAClosure()
    {
        $func = function (string $name, int $age) {
            return 'Hello ' . $name . ', you are ' . $age . ' years old';
        };

        $endPoint = new CacheableClosure($func);
        $serial = serialize($endPoint);
        $this->assertIsString($serial);
        /** @var CacheableCall $deserial */
        $deserial = unserialize($serial);
        $this->assertInstanceOf(CacheableClosure::class, $deserial);
        $result1 = $deserial->run(['Mike', 20]);
        $this->assertEquals('Hello Mike, you are 20 years old', $result1);
        $result2 = $deserial->run(['age' => 18, 'name' => 'John']);
        $this->assertEquals('Hello John, you are 18 years old', $result2);
    }

    public function testShouldHandleAnInstanceMethod()
    {
        $string = 'test page';
        $mock = new ControllerMock($string);
        $endPoint = CacheableMethod::build($mock, 'action');
        $serial = serialize($endPoint);
        $this->assertIsString($serial);
        $deserial = unserialize($serial);
        $this->assertInstanceOf(ControllerMock::class, $deserial->subject);
        $this->assertSame('page title : '.$string, $deserial->run());
        $this->assertSame('page title : '.$string, $deserial());
    }

    public function testShouldHandleAStaticMethod()
    {
        $wrapper = CacheableMethod::build(FactoryMock::class, 'create');
        $serial = serialize($wrapper);
        $this->assertIsString($serial);
        /** @var CacheableMethod $extract */
        $extract = unserialize($serial);
        $this->assertInstanceOf(CacheableMethod::class, $extract);
        $this->assertSame(FactoryMock::class, $extract->subject);
        $this->assertSame('create', $extract->method);
        $this->assertSame('new Mock created for serialize tests', $extract(['serialize tests']));
    }

    public function testShouldBeAbleToHandleAnInvokableObject()
    {
        $subject = new InvokableMock();
        $endPoint = new CacheableInvokable($subject);
        $serial = serialize($endPoint);
        $this->assertIsString($serial);
        $deserial = unserialize($serial);
        $this->assertInstanceOf(InvokableMock::class, $deserial->invokable);
        $this->assertSame('Hello John', $deserial->run(['John']));
    }
}
