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
 * Describe the way to
 * serialize / unserialize
 * non-serializable data
 *
 * @version 1.0.0
 */
interface Cacheable
{
    // public function run(array $parameters = []): mixed;
    // public function callable(): callable;
    public function encode():string;
    public function decode(string $serial):void;
}
