<?php

declare(strict_types=1);

namespace Ascetik\Cacheable\Factories;

use Ascetik\Cacheable\Callable\CacheableClosure;
use Ascetik\Cacheable\Callable\CacheableInvokable;
use Ascetik\Cacheable\Callable\CacheableMethod;
use Closure;
use InvalidArgumentException;

class CacheableFactory
{
    public static function wrapCall(callable $callable)
    {
        if (is_array($callable)) {
            return CacheableMethod::build(...$callable);
        }

        if ($callable instanceof Closure) {
            return new CacheableClosure($callable);
        }

        if (is_object($callable) && method_exists($callable, '__invoke')) {
            return new CacheableInvokable($callable);
        }
        throw new InvalidArgumentException('No use case matching with given parameters');
    }

    public static function wrapInstance()
    {
    }
}
