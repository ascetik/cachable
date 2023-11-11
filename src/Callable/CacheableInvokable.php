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
use Ascetik\Callapsule\Values\InvokableCall;

/**
 * Handle and serialize an Invokable instance
 *
 * @version 1.0.0
 */
class CacheableInvokable extends CacheableCall
{
    private InvokableCall $invokable;

    public function __construct(object $invokable)
    {
        $this->buildWrapper($invokable);
    }

    public function serialize(): string
    {
        $wrapper = new CacheableInstance($this->invokable->getCallable());
        return serialize($wrapper);
    }

    public function unserialize(string $data): void
    {
        /** @var CacheableInstance $wrapper */
        $wrapper = unserialize($data);
        $invokable = $wrapper->getInstance();
        $this->buildWrapper($invokable);
    }

    protected function getWrapper(): CallableType
    {
        return $this->invokable;
    }
    
    private function buildWrapper(object $invokable)
    {
        $this->invokable = InvokableCall::build($invokable);
    }
}
