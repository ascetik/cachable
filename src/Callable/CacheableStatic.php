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

namespace Ascetik\Cacheable\Callable;

use Ascetik\Cacheable\Types\CacheableCall;
use Ascetik\Callapsule\Values\StaticCall;

class CacheableStatic extends CacheableCall
{
    private StaticCall $wrapper;

    public function __construct(
        string $className,
        string $method
    ) {
        $this->buildWrapper($className, $method);
    }

    public function callable(): callable
    {
        return $this->wrapper->action();
    }

    public function serialize()
    {
        return serialize($this->wrapper->getCallable()->get());
    }

    public function unserialize(string $serial)
    {
        $this->buildWrapper(...unserialize($serial));
    }

    private function buildWrapper(string $className, string $method)
    {
        $this->wrapper = StaticCall::build($className, $method);
    }
}
