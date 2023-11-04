<?php

/**
 * This is part of the ascetik/cacheable package
 *
 * @package    Cacheable
 * @category   Type
 * @license    https://opensource.org/license/mit/  MIT License
 * @copyright  Copyright (c) 2023, Vidda
 * @author     Vidda <vidda@ascetik.fr>
 */

declare(strict_types=1);

namespace Ascetik\Cacheable\Types;

use Serializable;

/**
 * Main abstraction to handle callables
 *
 * @abstract
 * @version 1.0.0
 */
abstract class CacheableCall implements Cacheable, Serializable
{
    public function run(array $parameters = []): mixed
    {
        return call_user_func_array($this->callable(), $parameters);
    }

    public function serialize()
    {
        return $this->encode();
    }

    public function unserialize(string $data)
    {
        $this->decode($data);
    }
}
