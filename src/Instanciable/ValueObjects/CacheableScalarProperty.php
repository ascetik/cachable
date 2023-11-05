<?php

namespace Ascetik\Cacheable\Instanciable\ValueObjects;

use Ascetik\Cacheable\Types\CacheableProperty;

class CacheableScalarProperty  implements CacheableProperty
{
    public function __construct(
        public readonly string $property,
        public readonly mixed $content
    ) {
    }

    // ??
    public function getType(): string
    {
        return gettype($this->content);
    }

    public function encode(): string
    {
        return json_encode($this);
    }

    public function decode(string $serial): void
    {
        [$this->property, $this->content] = json_decode($serial, true);
    }
}
