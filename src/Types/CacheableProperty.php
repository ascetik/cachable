<?php

namespace Ascetik\Cacheable\Types;

interface CacheableProperty extends Cacheable
{
    public function getType(): string;
}
