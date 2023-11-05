<?php

namespace Ascetik\Cacheable\Instanciable;

use Ascetik\Cacheable\Types\Cacheable;

class CacheableInstance implements Cacheable
{
    public function __construct(private object $subject)
    {
    }

    public function encode(): string
    {
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
