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
use JsonSerializable;

/**
 * Handle and serialize an Invokable instance
 *
 * Missing serialization of a non-serializable instance
 * @version 0.1.0
 */
class CacheableInvokable extends CacheableCall
{
    public function __construct(public readonly object $invokable)
    {
    }

    public function callable(): callable
    {
        return $this->invokable;
    }

    public function serialize(): string
    {
        /**
         * Dans le cas d'un objet qui n'est pas Serializable
         * il faudrait scanner l'objet pour arriver à serializer le tout
         * Pour arriver à le deserializer après.
         */
        return serialize($this->invokable);
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
            $this->invokable = unserialize($data);
    }
}
