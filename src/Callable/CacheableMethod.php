<?php

namespace Ascetik\Cacheable\Callable;

use Ascetik\Cacheable\Types\CacheableCall;
use InvalidArgumentException;

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

    public function encode(): string
    {
        return serialize($this->callable());
    }

    public function decode(string $data): void
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
