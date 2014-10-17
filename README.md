ArgumentsResolver
=================
[![Build Status](https://secure.travis-ci.org/rybakit/arguments-resolver.svg?branch=master)](http://travis-ci.org/rybakit/arguments-resolver)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/rybakit/arguments-resolver/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/rybakit/arguments-resolver/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/rybakit/arguments-resolver/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/rybakit/arguments-resolver/?branch=master)

ArgumentsResolver allows you to determine the arguments to pass to a function or method.


## Installation

The recommended way to install ArgumentsResolver is through [Composer](http://getcomposer.org):

```sh
$ composer require rybakit/arguments-resolver:~1.0@dev
```


## Usage example

```php
use ArgumentsResolver\InDepthArgumentsResolver;

$greet = function ($username, DateTime $date, $greeting = 'Hello %s!') {
    // ...
};

$parameters = [
    'Welcome %s!',
    new DateTime(),
    ['foo'],
    'username' => 'Stranger',
    'bar',
];

$arguments = (new InDepthArgumentsResolver($greet))->resolve($parameters);
print_r($arguments);
```

The above example will output:

```php
Array
(
    [0] => Stranger
    [1] => DateTime Object (...)
    [2] => Welcome %s!
)
```


## Resolvers

The library ships with two resolvers, the [InDepthArgumentsResolver](#indepthargumentsresolver) and [KeyArgumentsResolver](#keyargumentsresolver).
They both expect a function to be supplied as a single constructor argument. The function can be any [callable](http://php.net/manual/en/language.types.callable.php), [a string representing a class method](http://php.net/manual/en/reflectionmethod.construct.php) or an instance of [ReflectionFunctionAbstract](http://php.net/manual/en/class.reflectionfunctionabstract.php):

```php
new InDepthArgumentsResolver(['MyClass', 'myMethod']);
new InDepthArgumentsResolver([new MyClass(), 'myMethod']);
new InDepthArgumentsResolver(['MyClass', 'myStaticMethod']);
new InDepthArgumentsResolver('MyClass::myStaticMethod');
new InDepthArgumentsResolver('MyClass::__construct');
new InDepthArgumentsResolver(['MyClass', '__construct']);
new InDepthArgumentsResolver(new MyClass());
new InDepthArgumentsResolver(function () {});
new InDepthArgumentsResolver('MyNamespace\my_function');
new InDepthArgumentsResolver(new ReflectionMethod('MyClass', 'myMethod'));
new InDepthArgumentsResolver(new ReflectionFunction('MyNamespace\my_function'));
```

There is also an utility class which helps in creating a reflection instance:

```php
use ArgumentsResolver\ReflectionFactory;

$reflection = ReflectionFactory::create('MyClass::__construct');
$resolver = new InDepthArgumentsResolver($reflection);
```


#### InDepthArgumentsResolver

In the `InDepthArgumentsResolver`, the decision about whether an argument matched the parameter value or not
is influenced by multiple factors, namely the argument's type, the class hierarchy (if it's an object),
the argument name and the argument position.

To clarify, consider each circumstance in turn:

*Argument type*

```php
function foo(array $array, stdClass $object, callable $callable) {}

(new InDepthArgumentsResolver('foo'))->resolve([
    ...
    function () {},    // $callable
    ...
    new stdClass(),    // $object
    ...
    [42],              // $array
    ...
]);
```

*Class hierarchy*

```php
function foo(Exception $e, RuntimeException $re) {}

(new InDepthArgumentsResolver('foo'))->resolve([
    ...
    new RuntimeException(),    // $re
    ...
    new Exception(),           // $e
    ...
]);
```

*Argument name*

```php
function foo($a, $b) {}

(new InDepthArgumentsResolver('foo'))->resolve([
    ...
    'c' => 3,
    'b' => 2,    // $b
    'a' => 1,    // $a
    ...
]);
```

*Argument position*

```php
function foo($a, $b) {}

(new InDepthArgumentsResolver('foo'))->resolve([
    1,   // $a
    2,   // $b
    ...
]);
```

#### KeyArgumentsResolver

The `KeyArgumentsResolver` is a very simple resolver which does the matching only by the argument name.
Therefore this requires parameters to be an associative array:

```php
function foo($a, array $b, $c = null) {}

(new KeyArgumentsResolver('foo'))->resolve([
    ...
    'b' => [],       // $b
    'a' => 1,        // $a
    'c' => 'bar',    // $c
    ...
]);
```


## Tests

ArgumentsResolver uses [PHPUnit](http://phpunit.de) for unit testing.
In order to run the tests, you'll first need to setup the test suite using composer:

```sh
$ composer install
```

You can then run the tests:

```sh
$ phpunit
```


## License

ArgumentsResolver is released under the MIT License. See the bundled [LICENSE](LICENSE) file for details.
