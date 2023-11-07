<?php

declare(strict_types=1);

namespace Ascetik\Cacheable\Test;

use Ascetik\Cacheable\Instanciable\DTO\CacheablePropertyRegistry;
use Ascetik\Cacheable\Instanciable\ValueObjects\CacheableCallableProperty;
use Ascetik\Cacheable\Instanciable\ValueObjects\CacheableCustomProperty;
use Closure;
use PHPUnit\Framework\TestCase;

class CacheablePropertyRegistryTest extends TestCase
{
    private CacheablePropertyRegistry $registry;

    protected function setUp(): void
    {
        $this->registry = new CacheablePropertyRegistry();
        $reference1 = new CacheableCustomProperty('string', 'hello');
        $reference2 = new CacheableCallableProperty('function', fn () => 'hello');;
        $this->registry->push($reference1, $reference2);
    }

    public function testInsertingReferences()
    {
        $this->assertCount(2, $this->registry->list());
    }

    public function testShouldSerializeRegistryContent()
    {
        $serial = serialize($this->registry);
        $this->assertIsString($serial);
    }

    public function testShouldUnserializeRegistryContent()
    {
        $serial = serialize($this->registry);
        /** @var CacheablePropertyRegistry $export */
        $export = unserialize($serial);
        $this->assertInstanceOf(CacheablePropertyRegistry::class, $export);
        $list =$export->list();
        $this->assertCount(2, $list);
        $this->assertSame('hello', $list->first()->getValue());
        $func = $list->last()->getValue();
        $this->assertInstanceOf(Closure::class, $func);
        $this->assertSame('hello', call_user_func($func));

    }
}
