<?php

/**
 * This is part of the ascetik/cacheable package
 *
 * @package    Cacheable
 * @category   Handler
 * @license    https://opensource.org/license/mit/  MIT License
 * @copyright  Copyright (c) 2023, Vidda
 * @author     Vidda <vidda@ascetik.fr>
 */

 declare(strict_types=1);

namespace Ascetik\Cacheable\Instanciable;

use Ascetik\Cacheable\Instanciable\DTO\CacheablePropertyRegistry;
use Ascetik\Cacheable\Types\Cacheable;
use Ascetik\Cacheable\Types\CacheableProperty;
use Ds\Set;
use ReflectionClass;

/**
 * Handle Serialization of an instance
 *
 * @version 1.0.0
 */
class CacheableInstance implements Cacheable
{
    private CacheablePropertyRegistry $references;

    public function __construct(private object $subject)
    {
        $this->init();
    }

    public function getClass(): string
    {
        return $this->subject::class;
    }

    public function getProperties(): Set
    {
        return $this->references->list();
    }

    public function serialize(): string
    {
        return serialize([
            $this->subject::class,
            $this->references
        ]);
    }

    public function unserialize(string $serial): void
    {
        /**
         * @var mixed $subject
         * @var CacheableProperty[] $props
         */
        [$subject, $props] = unserialize($serial);
        $reflection = new ReflectionClass($subject);
        $this->subject = $reflection->newInstanceWithoutConstructor();
        $this->references = $props;
        /** @var CacheableProperty $cacheable */
        foreach ($this->references->list() as $cacheable) {
            $propName = $cacheable->getName();
            if($reflection->hasProperty($propName))
            {
                $reflection->getProperty($propName)->setValue($this->subject, $cacheable->getValue());
            }
        }

    }

    public function getInstance(): object
    {
        return $this->subject;
    }

    private function init(): void
    {
        $this->references = new CacheablePropertyRegistry();
        $reflection = new ReflectionClass($this->subject);
        $properties = $reflection->getProperties();

        $this->references->assign(count($properties));
        foreach ($properties as $property) {
            $cacheable = CacheableProperty::create(
                $property->name,
                $property->getValue($this->subject)
            );
            $this->references->push($cacheable);
        }
    }
}
