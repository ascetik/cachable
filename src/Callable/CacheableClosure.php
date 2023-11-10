<?php

/**
 * This is part of the ascetik/cacheable package
 *
 * @package    Cacheable
 * @category   Value Object
 * @license    https://opensource.org/license/mit/  MIT License
 * @copyright  Copyright (c) 2023, Vidda
 * @author     Vidda <vidda@ascetik.fr>
 */

declare(strict_types=1);

namespace Ascetik\Cacheable\Callable;

use Ascetik\Cacheable\Types\CacheableCall;
use Ascetik\Callabubble\Values\ClosureCall;
use Closure;
use Opis\Closure\SerializableClosure;

/**
 * Handle and serialize a Closure
 *
 * @uses opis/closure Package
 * @version 1.0.0
 */
class CacheableClosure extends CacheableCall
{
    private ClosureCall $wrapper;

    public function __construct(Closure $callable)
    {
        $this->buildWrapper($callable);
    }

    public function callable(): callable
    {
        return $this->wrapper->action();
    }

    public function serialize(): string
    {
        $wrapper = new SerializableClosure($this->wrapper->getCallable());
        return serialize($wrapper);
    }

    public function unserialize(string $data): void
    {
        $deserial = unserialize($data);
        $this->buildWrapper($deserial->getClosure());
    }

    private function buildWrapper(Closure $callable)
    {
        $this->wrapper = new ClosureCall($callable);
    }
}
