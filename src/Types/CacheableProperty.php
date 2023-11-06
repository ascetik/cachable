<?php

/**
 * This is part of the ascetik/cacheable package
 *
 * @package    Cacheable
 * @category   Abstraction
 * @license    https://opensource.org/license/mit/  MIT License
 * @copyright  Copyright (c) 2023, Vidda
 * @author     Vidda <vidda@ascetik.fr>
 */

declare(strict_types=1);

namespace Ascetik\Cacheable\Types;

use Ascetik\Cacheable\Instanciable\CacheableInstance;
use Ascetik\Cacheable\Instanciable\ValueObjects\CacheableCallableProperty;
use Ascetik\Cacheable\Instanciable\ValueObjects\CacheableCustomProperty;
use Closure;

/**
 * Details the behavior of an instance
 * property and its value
 *
 * @abstract
 * @version 1.0.0
 */
abstract class CacheableProperty implements Cacheable
{
    public function __construct(protected string $name)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    abstract public function getValue(): mixed;

    public static function create(string $name, mixed $value): CacheableProperty
    {
        return match (true) {
            $value instanceof Closure => new CacheableCallableProperty($name, $value),
            is_object($value) => new CacheableInstance($value),
            default => new CacheableCustomProperty($name, $value)
        };
    }
}
