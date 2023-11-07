<?php

namespace Ascetik\Cacheable\Test\Mocks;

class InvokableMock
{
    public function __invoke(string $name)
    {
        return 'Hello '.$name;
    }
}
