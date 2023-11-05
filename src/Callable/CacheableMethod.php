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
use InvalidArgumentException;

/**
 * Handle and serialize a class/instance method
 *
 * @version 1.0.0
 */
class CacheableMethod extends CacheableCall
{
    private function __construct(
        public readonly object|string $subject,
        public readonly string $method
    ) {
    }

    public function callable(): callable
    {
        return [$this->subject, $this->method];
    }

    public function serialize(): string
    {
        return serialize($this->callable());
    }

    public function unserialize(string $data): void
    {
        [$this->subject, $this->method] = unserialize($data);
    }


    /**
     * Factory method
     *
     * @param  object|string $subject
     * @param  string        $method
     *
     * @throws InvalidArgumentException
     *
     * @return self
     */
    public static function build(array $callable): self
    {
        if (count($callable) != 2) {
            throw new InvalidArgumentException('Wrong parameters number');
        }

        [$subject, $method] = $callable;

        if (is_string($subject) && !class_exists($subject)) {
            throw new InvalidArgumentException('Class ' . $subject . ' not found');
        }
        if (!method_exists($subject, $method)) {
            throw new InvalidArgumentException('Method ' . $method . ' not implemented');
        }
        return new self($subject, $method);
    }
}
