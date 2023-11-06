<?php

/**
 * This is part of the ascetik/cacheable package
 *
 * @package    Cacheable
 * @category   interface Type
 * @license    https://opensource.org/license/mit/  MIT License
 * @copyright  Copyright (c) 2023, Vidda
 * @author     Vidda <vidda@ascetik.fr>
 */

 declare(strict_types=1);

namespace Ascetik\Cacheable\Types;

/**
 * Implemented by object reference classes
 * such as CacheableProperty or CacheableInstance
 *
 * @version 1.0.0
 */
interface CacheableObjectReference extends Cacheable
{
    public function getName(): string;
    public function getValue(): mixed;
}
