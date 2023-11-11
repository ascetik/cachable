<?php

declare(strict_types=1);

namespace Ascetik\Cacheable\Test;

use Ascetik\Cacheable\Callable\CacheableMethod;
use Ascetik\Cacheable\Callable\CacheableStatic;
use Ascetik\Cacheable\Test\Mocks\ControllerMock;
use Ascetik\Cacheable\Test\Mocks\FactoryMock;
use PHPUnit\Framework\TestCase;

class CacheableCallsTest extends TestCase
{
 

    public function testShouldHandleAStaticMethod()
    {
        $wrapper = new CacheableStatic(FactoryMock::class, 'create');
        $serial = serialize($wrapper);
        $this->assertIsString($serial);
        /** @var CacheableStatic $extract */
        $extract = unserialize($serial);
        $this->assertInstanceOf(CacheableStatic::class, $extract);
        [$subject,$method] = $extract->callable();
        $this->assertSame(FactoryMock::class, $subject);
        $this->assertSame('create', $method);
        $this->assertSame('new Mock created for serialize tests', $extract(['serialize tests']));
    }

}
