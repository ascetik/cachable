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

use Ascetik\Cacheable\Instanciable\CacheableInstance;
use Ascetik\Cacheable\Types\CacheableCall;
use Ascetik\Callapsule\Exceptions\MethodNotImplementedException;
use Ascetik\Callapsule\Values\MethodCall;

/**
 * Handle and serialize a class/instance method
 *
 * @version 1.0.0
 */
class CacheableMethod extends CacheableCall
{
    private MethodCall $wrapper;

    private function __construct(
        object $subject,
        string $method
    ) {
        $this->buildInstanceWrapper($subject, $method);
        $this->wrapper = MethodCall::build($subject, $method);
    }

    public function callable(): callable
    {
        return $this->wrapper->getCallable()->get();
    }

    public function serialize(): string
    {
        [$subject, $method] = $this->wrapper->getCallable()->get();
        $wrapper = new CacheableInstance($subject);
        return serialize([$wrapper, $method]);



        // if (is_string($this->subject)) {
        //     return serialize($this->callable());
        // }

        // $wrapper = new CacheableInstance($this->subject);
        // return serialize([$wrapper, $this->method]);
    }

    public function unserialize(string $data): void
    {
        /** @var CacheableInstance $subject */
        [$subject, $method] = unserialize($data);
        $this->buildInstanceWrapper($subject->getInstance(), $method);
        // $this->subject = $subject instanceof CacheableInstance
        //     ? $subject->getInstance()
        //     : $subject;
        // $this->method = $method;
    }

    private function buildInstanceWrapper(object $instance, string $method)
    {
        $this->wrapper = MethodCall::build($instance, $method);
    }

    /**
     * Factory method
     *
     * @param  object|string $subject
     * @param  string        $method
     *
     * @throws MethodNotImplementedException
     *
     * @return self
     */
    public static function build(object $subject, string $method): self
    {
        // if (is_string($subject) && !class_exists($subject)) {
        //     throw new InvalidArgumentException('Class ' . $subject . ' not found');
        // }
        if (!method_exists($subject, $method)) {
            throw new MethodNotImplementedException('Method ' . $method . ' not implemented');
        }
        return new self($subject, $method);
    }
}
