<?php

declare(strict_types=1);

namespace Ascetik\Cacheable\Instanciable;

use Ascetik\Cacheable\Types\CacheableObjectReference;
use Ascetik\Cacheable\Types\CacheableProperty;
use Ds\Set;
use ReflectionClass;

class CacheableInstance implements CacheableObjectReference
{
    // il nous faut un container.
    // il faut d'abord gérer la fabrication d'un CacheableObjectReference pour les objets
    public function __construct(private object $subject)
    {
        // CacheableInstance est seulement un gestionnaire

        // Il implemente CacheableObjectReference et utilise un Set de CacheableObjectReferences

        // il nous faut créer un container spécialisé pour faciliter la gestion

        /**
         * Ce container devra être rempli de CacheableObjectReferences qui seront serialisés.
         * On n'utilisera pas le jsonSerialize du Set. Il faudra sortir un tableau serialisé.
         *
         * à la deserialisation, il faudra reprendre le tableau et le refiler au conteneur pour le reconstruire.
         * Il faudra aussi retenir le nom de la classe d'origine de l'instance.
         */

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
            $props->add($cacheable);
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
        $this->subject = $reflection->newInstanceWithoutConstructor();

        var_dump('props', $props);
        foreach ($props as $cacheable) {
            // if(in_array($cacheable))
            var_dump($cacheable);
        }

        // var_dump($data);
    }

    public function getInstance(): object
    {
        return $this->subject;
    }
}
