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

use Ascetik\Callapsule\Types\CallableType;

/**
 * Main abstraction to handle callable serialization
 *
 * @abstract
 * @version 1.0.0
 */
abstract class CacheableCall extends CallableType implements Cacheable
{
    protected CallableType $wrapper;

    public function __invoke(iterable $parameters = []): mixed
    {
        return $this->apply($parameters);
    }

    public function apply(iterable $parameters = []): mixed
    {
        return $this->wrapper->apply($parameters);
    }


    public function action(): callable
    {
        return $this->wrapper->action();
    }

    public function getCallable(): object
    {
        return $this->wrapper->getCallable();
    }
}
