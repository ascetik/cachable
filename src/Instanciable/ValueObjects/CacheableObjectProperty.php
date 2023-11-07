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

use Ascetik\Cacheable\Instanciable\CacheableInstance;
use Ascetik\Cacheable\Types\CacheableProperty;

/**
 * Handle serialization of an instance
 *
 * @version 1.0.0
 */
class CacheableObjectProperty extends CacheableProperty
{
    public function __construct(string $name, private object $subject)
    {
        parent::__construct($name);
    }

    public function getValue(): object
    {
        return $this->subject;
    }

    public function serialize()
    {
        $cacheableInstance = new CacheableInstance($this->subject);
        return serialize([$this->name, $cacheableInstance]);
    }

    public function unserialize(string $data)
    {
        /** @var CacheableInstance $wrapper */
        [$this->name, $wrapper] = unserialize($data);
        $this->subject = $wrapper->getInstance();;
        
    }
}
