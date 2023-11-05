<?php

namespace Ascetik\Cacheable\Test;

use Ascetik\Cacheable\Instanciable\CacheableInstance;
use Ascetik\Cacheable\Test\Mocks\ControllerMock;
use PHPUnit\Framework\TestCase;

class CacheableInstanceTest extends TestCase
{
    public function testEncoding()
    {
        $instance = new ControllerMock('home page');
        $wrapper = new CacheableInstance($instance);
        $this->assertArrayHasKey('subject', $wrapper->data());
    }
}
