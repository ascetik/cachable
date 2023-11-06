<?php

namespace Ascetik\Cacheable\Instanciable;

use Ascetik\Cacheable\Instanciable\ValueObjects\CacheableClosureProperty;
use Ascetik\Cacheable\Instanciable\ValueObjects\CacheableCustomProperty;
use Ascetik\Cacheable\Types\Cacheable;
use Ascetik\Cacheable\Types\CacheableProperty;
use Closure;
use Ds\Set;
use Opis\Closure\SerializableClosure;
use ReflectionClass;

class CacheableInstance implements Cacheable
{
    private Set $props;
    public function __construct(private object $subject)
    {
        $this->props = new Set();
        // CacheableInstance est seulement un gestionnaire

        // Il n'implemente pas Cacheable mais utilise des Cacheables

        // Il n'est ni Serializable ni JsonSerializable mais utilisera les outils de serialization Ad Hoc
        // Mais NON ! CacheableInstance est justement celui que je veux encoder.
        // Il peut utiliser les outils qu'il veut, le tout est d'arriver à construire le serial et à rétablir le CacheableInstance.
        // CacheableInstance est un Cacheable qui contient des Cacheables.
        // je pars déjà sur la mauvaise piste...

        /**
         * Il va me falloir construire un Set contenant des instances CacheableProperty.
         *
         * CacheableProperty contient le nomm de la propriété, son type et son contenu.
         * Chacun d'entre eux est public readonly.
         * Ou bien alors on lui donne directement le ReflectionProperty et il est capable de se démmerder...
         * Non, je ne peux pas faire ça, il me faut accéder à l'instance visée. C'est un ValueObject qui contient des données
         *
         * @return array
         */
    }

    public function data(): Set
    {
        $props = new Set();
        $reflection = new ReflectionClass($this->subject);
        foreach ($reflection->getProperties() as $property) {

            $cacheable = CacheableProperty::create(
                $property->name,
                $property->getValue($this->subject)
            );
            // $value = $property->getValue($this->subject);
            // $cacheable =  $value instanceof Closure
            //     ? new CacheableClosureProperty($property->name, $value)
            //     : new CacheableCustomProperty($property->name, $value);
            $props->add($cacheable);
            // il faut tester le type

            // si c'est un string, int, float, bool, array ou Serializable, on le met dans un CacheableScalar (Cacheable)

            // si c'est une Closure, on a déjà un CacheableClosure dont on retourne le encode
        }
        return $props;
    }


    public function serialize(): string
    {
        $reflection = new ReflectionClass($this->subject);
        // echo $reflection->getName().PHP_EOL;
        return serialize([
            $this->subject::class,
            $this->data()->toArray()
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
        $instance = $reflection->newInstanceWithoutConstructor();

        var_dump('props',$props);
        foreach($props as $cacheable)
        {

        }

        // var_dump($data);
    }

    public function getInstance(): object
    {
        return $this->subject;
    }
}
