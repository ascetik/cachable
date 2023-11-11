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
use Ascetik\Callapsule\Values\InvokableCall;

/**
 * Handle and serialize an Invokable instance
 *
 * @version 1.0.0
 */
class CacheableInvokable extends CacheableCall
{
    private InvokableCall $call;

    public function __construct(object $invokable)
    {
        $this->buildWrapper($invokable);
    }

    public function callable(): callable
    {
        return $this->call->action();
    }

    public function serialize(): string
    {
        /**
         * Dans le cas d'un objet qui n'est pas Serializable
         * il faudrait scanner l'objet pour arriver à serializer le tout
         * Pour arriver à le deserializer après.
         */
        $wrapper = new CacheableInstance($this->call->getCallable());
        return serialize($wrapper);
    }

    public function unserialize(string $data): void
    {
        /**
         * ici aussi il faudrait deserializer notre objet data
         * Si ce n'est pas un Serializable, il faut tout de meme arriver à
         * deserializer
         *
         * Le mieux seraient de wrapper l'invokable dans une classe Serializable capabble d'enregistrer dans un tableau
         * toutes les props correctement serializées.
         * C'est encore cet objet là qui va nous permettre la deserialization.
         *
         * InstanceSerializer
         * ou SerializableInstance plutôt.
         * Elle implémente Serializable et utilise
         * ReflectionClass pour retrouver tout xce qu'il lui faut.
         */
        /** @var CacheableInstance $wrapper */
        $wrapper = unserialize($data);
        // var_dump($wrapper);
        $invokable = $wrapper->getInstance();
        $this->buildWrapper($invokable);
    }

    private function buildWrapper(object $invokable)
    {
        $this->call = InvokableCall::build($invokable);
    }
}
