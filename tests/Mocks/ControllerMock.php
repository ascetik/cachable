<?php

declare(strict_types=1);

namespace Ascetik\Cacheable\Test\Mocks;

class ControllerMock
{
    private string $unreacheable = 'restricted';
    
    public function __construct(public readonly string $title)
    {
        
    }
    public function action()
    {
        return 'page title : '.$this->title;
    }
}
