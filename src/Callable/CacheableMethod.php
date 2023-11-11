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
use Ascetik\Callapsule\Types\CallableType;
use Ascetik\Callapsule\Values\MethodCall;

/**
 * Handle and serialize a class/instance method
 *
 * @version 1.0.0
 */
class CacheableMethod extends CacheableCall
{
    protected MethodCall $wrapper;

    public function __construct(
        object $subject,
        string $method
    ) {
        $this->buildInstanceWrapper($subject, $method);
        $this->wrapper = MethodCall::build($subject, $method);
    }

    public function serialize(): string
    {
        [$subject, $method] = $this->wrapper->getCallable()->get();
        $wrapper = new CacheableInstance($subject);
        return serialize([$wrapper, $method]);
    }

    public function unserialize(string $data): void
    {
        /** @var CacheableInstance $subject */
        [$subject, $method] = unserialize($data);
        $this->buildInstanceWrapper($subject->getInstance(), $method);
    }

    protected function getWrapper(): MethodCall
    {
        return $this->wrapper;
    }
    private function buildInstanceWrapper(object $instance, string $method)
    {
        $this->wrapper = MethodCall::build($instance, $method);
    }
}
