<?php

declare(strict_types=1);

namespace Ascetik\Cacheable\Test\Mocks;

class ControllerMock
{
    public function __construct(private string $title)
    {
        
    }
    public function action()
    {
        return $this->title;
    }
}
