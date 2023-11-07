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
    public function run(iterable $parameters = []): mixed
    {
        return call_user_func_array($this->callable(), iterator_to_array($parameters));
    }

    abstract public function callable(): callable;

}
