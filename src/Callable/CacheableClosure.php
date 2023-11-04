<?php

namespace Ascetik\Cacheable\Callable;

use Ascetik\Cacheable\Types\CacheableCall;
use Closure;
use Opis\Closure\SerializableClosure;

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

    // pour l'instant, methode perso. Pourrait être héritée
    public function decode(string $data): void
    {
        $deserial = unserialize($data);
        $this->callable = $deserial->getClosure();
    }
}
