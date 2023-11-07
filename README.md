# Cachable

Convert Closures and objects to strings.

## Release notes

> v0.1.0 (draft)

- serialize/unserialize functions, class/instance methods, invokable instances
- serialize/unserialize instances
- Factory available to build Cacheable element.

## Purpose

Each time a client switches the page or submits some data, the server has to build whole system, with services, routes, middlewares...
I wondered if it was possible to store some instances or functions in cache for faster system build.
The main problem was the serialization of a **Closure**, which may be used by a router or a service container.

A short time was necessary to find the **opis/closure** package. This was the base to find out how to resolve my problem.
This package may be still incomplete. I have to test it in a production context to get some feedback with some logs.
Self-made logger package is still not done.

## Vocabulary

**Cacheable** is the main interface. It just extends **Serializable** interface and nothing else for now.
**CacheableCall** abstracts the idea of a wrapper able to serialize any callable.
**CacheableInstance** extends an instance to be fully serializable.
**CacheableProperty** holds the name and the value of each **CacheableInstance** wrapped instance.

## Descriptions & Exposed Methods

> Notice : The use of serialize and unserialize methods is not recommended and will certainly lead to serialization inconsistencies. Use native serialize/unserialize functions instead.

### CacheableFactory (not Cacheable itself)

- wrapCall(**callable**): **CacheableCall**                 : Returns a **CacheableCall** instance
- wrapInstance(**object**): **CacheableInstance**           : Returns a **CacheableInstance** instance
- wrapProperty(**string**, **mixed**): **CacheableProperty** : Returns a **CacheableProperty**

### CacheableCall

- callable(): **callable**     : Returns registered callable as is
- run(**iterable**): **mixed** : Calls registered callable

> **CacheableCall** is invokable to keep it simple to use on runtime

### CacheableInstance

- getClass(): **string**       : Returns registered class name
- getProperties(): **Ds\Set**  : Returns a container of **CacheableProperties** built from registered instance properties
- getInstance(): **object**    : Returns registered instance

### CacheableProperty

- getName(): **string**        : Returns property name
- getValue(): **mixed**        : Returns property value

## Usage

### Callable Wrap

The **CacheableFactory** provides methods to build **Cacheable** instances for callable types.
There are three sorts of wrapper to serialize a callable :

```php

// Closure use case
$func = function(string $name): string {
    return 'Closure : hello '.$name;
}

// class method use case
class Greeter
{
    public function greet($name): string
    {
        return 'Method : hello ' . $name;
    }
}

// invokable class use case
class InvokableGreeter
{
    public function __invoke(string $name): string
    {
        return 'Invkable : hello ' . $name;
    }
}

$closureWrapper = CacheableFactory::wrapCall($func); // returns a CacheableClosure instance
$methodWrapper = CacheableFactory::wrapCall(new Greeter()); // returns a CacheableMethod instance
$invokableWrapper = CacheableFactory::wrapCall(new InvokableGreeter()); // returns a CacheableInvokable instance

```

As they all are **Cacheable**, so **Serializable**, serialization is easy :

```php

$serial = serialize($closureWrapper);

```

And unserialization is easy too :

```php

$serial = goAndFindInCache('something-satisfying-my-needs'); // file, external service, SQL, noSQL, hyper-speed keyboard typing world champion...
$wrapper = unserialize($serial);
echo $wrapper->run(['John']); // prints 'Closure : hello John'
// A CacheableCall is invokable
echo $wrapper(['John']); // same result

```

### Instances Wrap

```php

$instance = new MyInstance();
$wrapper = CacheableFactory::wrapInstance($instance);
$serial = serialize($wrapper);

// get wrapper back
$extractedWrapper = unserialize($serial);
$extractedInstance = $extractedWrapper->getInstance();
$extractedInstance->doWhatIsExpected();
// Or use the extracted wrapper directly
$extractedWrapper->doWhatIsExpected();
$property = $extractedWrapper->someProperty();

```
When using wrapper magic methods, Exceptions are thrown if :
- called method does not exist
- property does not exist
- property is public


## Finalities

So now, i have to test if a cached router system is more efficient than the traditionnal implementation which load all routes all the times.
My Krono package will certainly be useful.
