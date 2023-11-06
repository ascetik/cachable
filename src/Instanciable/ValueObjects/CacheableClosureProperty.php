<?php

namespace Ascetik\Cacheable\Instanciable\ValueObjects;

use Ascetik\Cacheable\Types\CacheableProperty;
use Closure;
use Opis\Closure\SerializableClosure;

class CacheableClosureProperty extends CacheableProperty
{
    public function __construct(string $name, private Closure $call)
    {
        parent::__construct($name);
    }

    public function getType(): string
    {
        return Closure::class;
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
