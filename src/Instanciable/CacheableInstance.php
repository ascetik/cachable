<?php

namespace Ascetik\Cacheable\Instanciable;

use Ascetik\Cacheable\Types\Cacheable;
use ReflectionClass;

class CacheableInstance implements Cacheable
{
    public function __construct(private object $subject)
    {
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

    public function data(): array
    {
        return [
            'subject' => $this->subject::class,
            'serial' => $this->stringify()
        ];
    }

    private function stringify()
    {
        $reflection = new ReflectionClass($this->subject);
        foreach($reflection->getProperties() as $property){
            // il faut tester le type

            // si c'est un string, int, float, bool, array ou Serializable, on le met dans un CacheableScalar (Cacheable)

            // si c'est une Closure, on a déjà un CacheableClosure dont on retourne le encode
        }
    }

    public function encode(): string
    {
        $reflection = new ReflectionClass($this->subject);
        foreach($reflection->getProperties() as $property){
            $name = $property->name;
            $type = $property->getType();
            $content = $property->getValue($this->subject);
            // il faut tester le type

            // si c'est un string, int, float, bool, array ou Serializable, on le met dans un CacheableScalar (Cacheable)

            // si c'est une Closure, on a déjà un CacheableClosure dont on retourne le encode
        }
        /**
         * C'est là qu'on va avoir la magie de la serialization
         *
         * Les props natives sont :
         * - string
         * - int
         * - float
         * - bool
         * - array
         * - Serializable
         * - JsonSerializable
         *
         * Si les props ne contiennet que des types comme ceux-là, on serialize directement l'objet.
         *
         * Si on tombe sur :
         * - Closure
         * - objet qui n'est pas serializable
         *
         * on serialize ces trucs.
         * Pour les CLosures, on sait déjà faire.
         * Pour un objet non serializable, il faudra une autre instance de CacheableInstance.
         *
         * Soyons clair dans le fonctionnement de la serialization :
         * On serialize un objet qui va contenir un tableau associatif ou même un Set (JsonSerailizable).
         * Les valeurs seront des objets eux-mêmes serializables à leur manière (serial, json...)
         * qui contiendront :
         * - le nom de la props
         * - le fqcn du type
         * - les données contenues par cet objet au moment de la serialization, elles -même serialisées.
         *
         * C'est par reflection que l'on pourra obtenir les données
         * et rétablir les instances. Elles n'auront pas la même référence en mémoire mais on s'en fout.
         * L'important est de pouvoir restaurer rapidement des services, des routes et des machins comme ça,
         * sans trop charger la mule bien entendu. On ne stockera que des petites choses.
         */
        $test = '';
        // Il faut déjà vérifier les types des propriétés

        // on doit faire un match avec tous les types.

        // les données de type scalaire sont directement encodées telles quelles.

        // les autres passent par un wrapper pour contenir le serial ad hoc

        // types scalaires et wrapper passent à la moulinette pour etre encodées dans un Set,
        // en json, du coup... On verra les petites subtilités mais on va tâcher de se servir de json s'il est dispo
        // meme en serialization. De toutes façons, notre wrapper contient tout ce qu'il faut !
        return '';
    }

    public function decode(string $serial): void
    {
    }
}
