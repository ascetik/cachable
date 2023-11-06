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

class CacheableCustomProperty extends CacheableProperty
{
    public function __construct(
        string $name,
        private mixed $content
    ) {
        parent::__construct($name);
    }

    public function getType(): string
    {
        return gettype($this->name);
    }

    public function getValue(): mixed
    {
        return $this->content;
    }

    public function serialize(): string
    {
        return json_encode([$this->name, $this->content]);
    }

    public function unserialize(string $serial): void
    {
        [$this->name, $this->content] = json_decode($serial, true);
    }
}
