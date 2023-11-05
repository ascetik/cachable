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
    public function __construct(private Closure $callable)
    {
    }

    public function callable(): callable
    {
        return $this->callable;
    }

    public function encode(): string
    {
        $wrapper = new SerializableClosure($this->callable);
        return serialize($wrapper);
    }

    public function decode(string $data): void
    {
        $deserial = unserialize($data);
        $this->callable = $deserial->getClosure();
    }
}
