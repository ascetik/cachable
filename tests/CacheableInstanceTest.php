<?php

namespace Ascetik\Cacheable\Test;

use Ascetik\Cacheable\Instanciable\CacheableInstance;
use Ascetik\Cacheable\Instanciable\ValueObjects\CacheableCustomProperty;
use Ascetik\Cacheable\Test\Mocks\ControllerMock;
use PHPUnit\Framework\TestCase;

class CacheableInstanceTest extends TestCase
{
    public function testEncoding()
    {
        $wrapper = new CacheableInstance(new ControllerMock('home page'));
        $data = $wrapper->data();
        /** @var CacheableCustomProperty $first */
        $first = $data->first();
        $this->assertInstanceOf(CacheableCustomProperty::class, $first);
        $this->assertSame($first->getName(), 'title');
        $this->assertSame($first->getType(), 'string');
        $this->assertSame('home page', $first->getValue());
    }
}
