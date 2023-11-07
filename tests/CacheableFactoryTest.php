<?php

namespace Ascetik\Cacheable\Test;

use Ascetik\Cacheable\Callable\CacheableClosure;
use Ascetik\Cacheable\Callable\CacheableInvokable;
use Ascetik\Cacheable\Callable\CacheableMethod;
use Ascetik\Cacheable\Factories\CacheableFactory;
use Ascetik\Cacheable\Test\Mocks\ControllerMock;
use Ascetik\Cacheable\Test\Mocks\InvokableMock;
use PHPUnit\Framework\TestCase;

class CacheableFactoryTest extends TestCase
{
    public function testFactoryShouldCreateAClosureWrapper()
    {
        $this->assertInstanceOf(CacheableClosure::class, CacheableFactory::wrapCall(fn () => 'hello'));
    }

    public function testShouldCreateAMethodWrapper()
    {
        $this->assertInstanceOf(CacheableMethod::class, CacheableFactory::wrapCall([new ControllerMock('title'), 'action']));
    }

    public function testShouldCreateAnInvokableInstanceWrapper()
    {
        $this->assertInstanceOf(CacheableInvokable::class, CacheableFactory::wrapCall(new InvokableMock()));
    }
}
