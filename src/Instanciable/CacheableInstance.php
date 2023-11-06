<?php

declare(strict_types=1);

namespace Ascetik\Cacheable\Instanciable;

use Ascetik\Cacheable\Instanciable\DTO\CacheableObjectReferenceRegistry;
use Ascetik\Cacheable\Types\Cacheable;
use Ascetik\Cacheable\Types\CacheableProperty;
use Ds\Set;
use ReflectionClass;

class CacheableInstance implements Cacheable
{

    private CacheableObjectReferenceRegistry $references;
    // il nous faut un container.
    // il faut d'abord gérer la fabrication d'un CacheableObjectReference pour les objets
    public function __construct(private object $subject)
    {
        $this->init();
    }

    public function getName(): string
    {
        return $this->reflection()->getName();
    }

    public function getValue(): object
    {
        return $this->subject;
    }

    private function init(): void
    {
        $this->references = new CacheableObjectReferenceRegistry();
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
        // var_dump(class_exists($subject));
        $reflection = new ReflectionClass($subject);
        // il faut voir si on a un constructeur?
        $this->subject = $reflection->newInstanceWithoutConstructor();
        $this->references = $props;
        // echo 'subject : '. $subject . PHP_EOL;
        // var_dump('props', $props);
        /** @var CacheableProperty $cacheable */
        foreach ($this->references->list() as $cacheable) {
            // if(in_array($cacheable))
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

    private function reflection(): ReflectionClass
    {
        return new ReflectionClass($this->subject);
    }
}
