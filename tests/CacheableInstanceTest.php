<?php

declare(strict_types=1);

namespace Ascetik\Cacheable\Test;

use Ascetik\Cacheable\Instanciable\CacheableInstance;
use Ascetik\Cacheable\Instanciable\ValueObjects\CacheableCustomProperty;
use Ascetik\Cacheable\Test\Mocks\ControllerMock;
use PHPUnit\Framework\TestCase;

class CacheableInstanceTest extends TestCase
{
    public function testInstanciationOfCacheableInstance()
    {
        $wrapper = new CacheableInstance(new ControllerMock('home page'));
        $this->assertSame(ControllerMock::class, $wrapper->getName());
        $data = $wrapper->getProperties();
        $this->assertCount(1, $data);
        /** @var CacheableCustomProperty $first */
        $first = $data->first();
        $this->assertInstanceOf(CacheableCustomProperty::class, $first);
        $this->assertSame($first->getName(), 'title');
        $this->assertSame('home page', $first->getValue());
    }

    public function testSerializationOfACacheableInstance()
    {
        $wrapper = new CacheableInstance(new ControllerMock('home page'));
        $serial = serialize($wrapper);
        $this->assertIsString($serial);
    }

    public function testUnserializationOfACacheableInstance()
    {
        $wrapper = new CacheableInstance(new ControllerMock('home page'));
        $serial = serialize($wrapper);
        /** @var CacheableInstance $extract */
        $extract = unserialize($serial);
        $this->assertInstanceOf(CacheableInstance::class, $extract);
        $subject = $extract->getInstance();
        $this->assertInstanceOf(ControllerMock::class, $subject);
        $this->assertSame('home page', $subject->action());
    }
}
