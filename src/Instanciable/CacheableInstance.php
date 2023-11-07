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

use Ascetik\Cacheable\Factories\CacheableFactory;
use Ascetik\Cacheable\Instanciable\DTO\CacheablePropertyRegistry;
use Ascetik\Cacheable\Types\Cacheable;
use Ascetik\Cacheable\Types\CacheableProperty;
use BadMethodCallException;
use Ds\Set;
use OutOfBoundsException;
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

    public function __call($method, $arguments): mixed
    {
        if (!method_exists($this->subject, $method)) {
            throw new BadMethodCallException('The "' . $method . '" method is not implemented.');
        }
        return call_user_func_array([$this->subject, $method], $arguments);
    }

    public function __get($name): mixed
    {
        $reflection = new ReflectionClass($this->subject);
        if(!$reflection->hasProperty($name)){
            throw new OutOfBoundsException('The property "' . $name . '" does not exist.');
        }

        $property = $reflection->getProperty($name);
        if(!$property->isPublic()){
            throw new OutOfBoundsException('The property "' . $name . '" is out of scope.');
        }
        return $property->getValue($this->subject);
    }

    public function getClass(): string
    {
        return $this->subject::class;
    }

    public function getProperties(): Set
    {
        return $this->references->list();
    }

    public function getInstance(): object
    {
        return $this->subject;
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
         * @var class-string $subject
         * @var CacheableProperty[] $props
         */
        [$subject, $props] = unserialize($serial);
        $reflection = new ReflectionClass($subject);
        $this->subject = $reflection->newInstanceWithoutConstructor();
        $this->references = $props;
        /** @var CacheableProperty $cacheable */
        foreach ($this->references->list() as $cacheable) {
            $propName = $cacheable->getName();
            if ($reflection->hasProperty($propName)) {
                $reflection->getProperty($propName)->setValue($this->subject, $cacheable->getValue());
            }
        }
    }

    private function init(): void
    {
        $this->references = new CacheablePropertyRegistry();
        $reflection = new ReflectionClass($this->subject);
        $properties = $reflection->getProperties();

        $this->references->assign(count($properties));
        foreach ($properties as $property) {
            $cacheable = CacheableFactory::wrapProperty(
                $property->name,
                $property->getValue($this->subject)
            );
            $this->references->push($cacheable);
        }
    }
}
