<?php

namespace Ascetik\Cacheable\Test\Mocks;

use Closure;

class InvokablePropertyMock
{

    public function __construct(private Closure $call)
    {
    }

    public function __invoke(string $title)
    {
        return call_user_func($this->call, $title);
    }
}
