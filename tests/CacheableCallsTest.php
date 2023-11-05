<?php

declare(strict_types=1);

namespace Ascetik\Cacheable\Test;

use Ascetik\Cacheable\Callable\CacheableClosure;
use Ascetik\Cacheable\Callable\CacheableInvokable;
use Ascetik\Cacheable\Callable\CacheableMethod;
use Ascetik\Cacheable\Test\Mocks\ControllerMock;
use Ascetik\Cacheable\Test\Mocks\InvokableMock;
use Ascetik\Cacheable\Types\CacheableCall;
use PHPUnit\Framework\TestCase;

class CacheableCallsTest extends TestCase
{
    /**
     * Ces test devriendront bientot obsolète.
     * Ce n'est pas au package du Router de s'occuper de cache.
     * C'est le FrameWork, avec son fonctionnement interne, qui devra mettre en cache
     * la RoadMap à sa manière en utilisant un autre package pour le cache et celui du Cachable à venir
     * Le package Cachable-core contiendra les abstractions pricipales. J'aimerais bien faire tout ça en JSON...
     * Le package cachable-closure se spécialisera dans les CLosures
     * Le package cachable-instance se spécialisera dans la mise en cache d'objets, Serializable ou non
     * Le package cachable-method pourrait utiliser un CachableInstance
     * Le package cachable-invoke pourrait utiliser un CachableInstance
     * à voir...
     */
    public function testShouldSerializeAClosure()
    {
        $func = function (string $name, int $age) {
            return 'Hello ' . $name . ', you are ' . $age . ' years old';
        };

        $endPoint = new CacheableClosure($func);
        $serial = serialize($endPoint);
        $this->assertIsString($serial);
        $deserial = unserialize($serial);
        $this->assertInstanceOf(CacheableClosure::class, $deserial);
        $result1 = $deserial->run(['Mike', 20]);
        $this->assertEquals('Hello Mike, you are 20 years old', $result1);
        $result2 = $deserial->run(['age' => 18, 'name' => 'John']);
        $this->assertEquals('Hello John, you are 18 years old', $result2);
    }

    public function testShouldSerializeAClassMethod()
    {
        $string = 'test page';
        $mock = new ControllerMock($string);
        $endPoint = CacheableMethod::build([$mock, 'action']);
        $serial = serialize($endPoint);
        $this->assertIsString($serial);
        $deserial = unserialize($serial);
        $this->assertInstanceOf(ControllerMock::class, $deserial->subject);
        $this->assertSame($string, $deserial->run());
    }

    public function testShouldBeAbleToSerializeAnInvokableObject()
    {
        $subject = new InvokableMock();
        $endPoint = new CacheableInvokable($subject);
        $serial = serialize($endPoint);
        $this->assertIsString($serial);
        $deserial = unserialize($serial);
        $this->assertInstanceOf(InvokableMock::class, $deserial->invokable);
        $this->assertSame('Hello John', $deserial->run(['John']));
    }

    public function testEndPointFactoryMethod()
    {
        $this->assertInstanceOf(CacheableClosure::class, CacheableCall::create(fn () => 'Hello'));
        $this->assertInstanceOf(CacheableMethod::class, CacheableCall::create([new ControllerMock('title'), 'action']));
        $this->assertInstanceOf(CacheableInvokable::class, CacheableCall::create(new InvokableMock()));
    }
}
