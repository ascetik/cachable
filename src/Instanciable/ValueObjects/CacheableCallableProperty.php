<?php

/**
 * This is part of the ascetik/cacheable package
 *
 * @package    Cacheable
 * @category   Cacheable property
 * @license    https://opensource.org/license/mit/  MIT License
 * @copyright  Copyright (c) 2023, Vidda
 * @author     Vidda <vidda@ascetik.fr>
 */

declare(strict_types=1);

namespace Ascetik\Cacheable\Instanciable\ValueObjects;

use Ascetik\Cacheable\Types\CacheableProperty;
use Closure;
use Opis\Closure\SerializableClosure;

/**
 * Handle serialization of a Closure
 *
 * @uses Opis\Closure\SerializableClosure $wrapper
 * @version 1.0.0
 */
class CacheableCallableProperty extends CacheableProperty
{
    public function __construct(string $name, private Closure $call)
    {
        parent::__construct($name);
    }

    public function getValue(): Closure
    {
        return $this->call;
    }

    public function serialize(): string
    {
        $wrapper = new SerializableClosure($this->call);
        return serialize([$this->name, $wrapper]);
    }

    public function unserialize(string $serial): void
    {
        /** @var SerializableClosure $wrapper */
        [$this->name, $wrapper] = unserialize($serial);
        $this->call = $wrapper->getClosure();
    }
}
