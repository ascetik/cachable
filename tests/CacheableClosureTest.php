<?php

namespace Ascetik\Cacheable\Test;

use Ascetik\Cacheable\Callable\CacheableClosure;
use Ascetik\Cacheable\Types\CacheableCall;
use PHPUnit\Framework\TestCase;

class CacheableClosureTest extends TestCase
{
    private CacheableClosure $cacheable;

    protected function setUp(): void
    {
        $func = function (string $name, int $age) {
            return 'Hello ' . $name . ', you are ' . $age . ' years old';
        };

        $this->cacheable = new CacheableClosure($func);
    }

    public function testShouldSerializeAClosure()
    {
        $serial = serialize($this->cacheable);
        $this->assertIsString($serial);
    }

    public function testShouldUnserializeAClosure()
    {
        $serial = serialize($this->cacheable);
        /** @var CacheableClosure $deserial */
        $deserial = unserialize($serial);
        $this->assertInstanceOf(CacheableClosure::class, $deserial);
    }

    public function testShouldReturnExpectedStringFromClosure()
    {
        $serial = serialize($this->cacheable);
        /** @var CacheableClosure $deserial */
        $deserial = unserialize($serial);

        $this->assertEquals(
            'Hello Mike, you are 20 years old',
            $deserial->apply(['Mike', 20])
        );

        $this->assertEquals(
            'Hello John, you are 18 years old',
            $deserial->apply(['age' => 18, 'name' => 'John'])
        );
    }
}
