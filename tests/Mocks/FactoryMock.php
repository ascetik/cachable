<?php

namespace Ascetik\Cacheable\Test\Mocks;

class FactoryMock
{
    public static function create(string $subject): string
    {
        return 'new Mock created for '.$subject;
    }
}
