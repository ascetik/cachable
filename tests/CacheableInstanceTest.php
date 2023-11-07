<?php

declare(strict_types=1);

namespace Ascetik\Cacheable\Test;

use Ascetik\Cacheable\Instanciable\CacheableInstance;
use Ascetik\Cacheable\Instanciable\ValueObjects\CacheableCustomProperty;
use Ascetik\Cacheable\Test\Mocks\ControllerMock;
use BadMethodCallException;
use OutOfBoundsException;
use PHPUnit\Framework\TestCase;

class CacheableInstanceTest extends TestCase
{
    private CacheableInstance $wrapper;

    protected function setUp(): void
    {
        $this->wrapper = new CacheableInstance(new ControllerMock('home page'));
    }

    public function testInstanciationOfCacheableInstance()
    {
        $this->assertSame(ControllerMock::class, $this->wrapper->getClass());
        $data = $this->wrapper->getProperties();
        $this->assertCount(2, $data);
        /** @var CacheableCustomProperty $first */
        $last = $data->last();
        $this->assertInstanceOf(CacheableCustomProperty::class, $last);
        $this->assertSame($last->getName(), 'title');
        $this->assertSame('home page', $last->getValue());
    }

    public function testSerializationOfACacheableInstance()
    {
        $serial = serialize($this->wrapper);
        $this->assertIsString($serial);
    }

    public function testUnserializationOfACacheableInstance()
    {
        $serial = serialize($this->wrapper);
        /** @var CacheableInstance $extract */
        $extract = unserialize($serial);
        $this->assertInstanceOf(CacheableInstance::class, $extract);
        $subject = $extract->getInstance();
        $this->assertInstanceOf(ControllerMock::class, $subject);
        $this->assertSame('page title : home page', $subject->action());
    }

    public function testCallMagicMethod()
    {
        $serial = serialize($this->wrapper);
        /** @var CacheableInstance $extract */
        $extract = unserialize($serial);
        $this->assertSame('page title : home page', $extract->action());
    }

    public function testGetMagicMethod()
    {
        $serial = serialize($this->wrapper);
        /** @var CacheableInstance $extract */
        $extract = unserialize($serial);
        $this->assertSame('home page', $extract->title);
    }

    public function testShouldThrowExceptionOnUnimplementedMethod()
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('The "whatEver" method is not implemented.');
        $serial = serialize($this->wrapper);
        /** @var CacheableInstance $extract */
        $extract = unserialize($serial);
        $extract->whatEver();
    }

    public function testShouldThrowExceptionOnUnexistingProperty()
    {
        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage('The property "whatEver" does not exist.');
        $serial = serialize($this->wrapper);
        /** @var CacheableInstance $extract */
        $extract = unserialize($serial);
        $extract->whatEver;
    }

    public function testShouldThrowExceptionOnPrivateProperty()
    {
        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage('The property "unreacheable" is out of scope.');
        $serial = serialize($this->wrapper);
        /** @var CacheableInstance $extract */
        $extract = unserialize($serial);
        $extract->unreacheable;
    }


}
