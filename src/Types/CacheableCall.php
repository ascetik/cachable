<?php

/**
 * This is part of the ascetik/cacheable package
 *
 * @package    Cacheable
 * @category   Value Object Type
 * @license    https://opensource.org/license/mit/  MIT License
 * @copyright  Copyright (c) 2023, Vidda
 * @author     Vidda <vidda@ascetik.fr>
 */

declare(strict_types=1);

namespace Ascetik\Cacheable\Types;

use Ascetik\Cacheable\Callable\CacheableClosure;
use Ascetik\Cacheable\Callable\CacheableInvokable;
use Ascetik\Cacheable\Callable\CacheableMethod;
use Closure;
use InvalidArgumentException;
use Serializable;

/**
 * Main abstraction to handle callable serialization
 *
 * @abstract
 * @version 1.0.0
 */
abstract class CacheableCall implements Cacheable
{
    public function run(array $parameters = []): mixed
    {
        return call_user_func_array($this->callable(), $parameters);
    }

    abstract public function callable(): callable;

    final public static function create(callable $callable): CacheableCall
    {
        if (is_array($callable)) {
            return CacheableMethod::build($callable);
        }

        if ($callable instanceof Closure) {
            return new CacheableClosure($callable);
        }

        if (is_object($callable) && method_exists($callable, '__invoke')) {
            return new CacheableInvokable($callable);
        }
        throw new InvalidArgumentException('No use case matching with given parameters');
    }
}
