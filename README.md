CallableArgumentsResolver
=========================
[![Build Status](https://secure.travis-ci.org/rybakit/callable-arguments-resolver.png?branch=master)](http://travis-ci.org/rybakit/callable-arguments-resolver)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/rybakit/callable-arguments-resolver/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/rybakit/callable-arguments-resolver/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/rybakit/callable-arguments-resolver/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/rybakit/callable-arguments-resolver/?branch=master)

CallableArgumentsResolver allows you to determine the arguments to pass to the callable.



## Installation

The recommended way to install CallableArgumentsResolver is through [Composer](http://getcomposer.org):

```sh
$ composer require rybakit/callable-arguments-resolver:~1.0@dev
```



## Usage example

```php
use CallableArgumentsResolver as f;

$informer = function ($username, DateTime $date, $greeting = 'Hello %s!') {
    printf($greeting, $username);
    printf("\nToday is the %s.", $date->format('jS \of F Y'));
};

$parameters = [
    new DateTime(),
    'Welcome %s!',
    ['not an argument'],
    'username' => 'Stranger',
    'not an argument',
];

call_user_func_array($informer, f\resolve_arguments($informer, $parameters));
```

The above example will output something similar to:

```
Welcome Stranger!
Today is the 3rd of September 2013.
```

In a case you need to resolve arguments more than once for the same callable during
the execution of the script, it's recommended to make use of the `CallableArgumentsResolver` class:

```php
use CallableArgumentsResolver\CallableArgumentsResolver;

...

$resolver = new CallableArgumentsResolver($informer);

call_user_func_array(
    $resolver->getCallable(),
    $resolver->resolveArguments($parameters1)
);

...

call_user_func_array(
    $resolver->getCallable(),
    $resolver->resolveArguments($parameters2)
);
```



## Adapters

Adapters are used to encapsulate the logic about how to resolve callable arguments into the passed parameters.
The library ships with two adapters, the `InDepthAdapter` and `KeyAdapter`. By default,
the `InDepthAdapter` is used. To use a different adapter, simple pass it as the last argument
to the `resolve_arguments` function or `CallableArgumentsResolver` constructor:

```php
use CallableArgumentsResolver\Adapter\KeyAdapter;

...

$args = f\resolve_arguments($callable, $parameters, new KeyAdapter());
// or
$resolver = new CallableArgumentsResolver($callable, new KeyAdapter());
```

#### InDepthAdapter

In the `InDepthAdapter`, the decision about whether an argument matched the parameter value or not
is influenced by multiple factors, namely the argument's type, the class hierarchy (if it's an object),
the argument name and the argument position.

To clarify, consider each circumstance in turn:

*Argument type*

```php
function foo(array $array, stdClass $object, callable $callable) {}

$resolver->resolveArguments('foo', [
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

$resolver->resolveArguments('foo', [
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

$resolver->resolveArguments('foo', [
    ...
    'c' => 3,
    'a' => 1,    // $a
    'b' => 2,    // $b
    ...
]);
```

*Argument position*

```php
function foo($a, $b) {}

$resolver->resolveArguments('foo', [
    1,   // $a
    2,   // $b
    ...
]);
```

#### KeyAdapter

The `KeyAdapter` is a very simple adapter which does the matching only by the argument name.
Therefore this requires parameters to be an associative array:

```php
function foo($a, array $b, $c = null) {}

$resolver->resolveArguments('foo', [
    ...
    'b' => [],       // $b
    'a' => 1,        // $a
    'c' => 'bar',    // $c
    ...
]);
```


#### Custom adapter

Creating your own adapter is as easy as implementing the [Adapter](src/Adapter/Adapter.php) interface.



## Tests

CallableArgumentsResolver uses [PHPUnit](http://phpunit.de) for unit testing.
In order to run the tests, you'll first need to setup the test suite using composer:

```sh
$ composer install
```

You can then run the tests:

```sh
$ phpunit
```



## License

CallableArgumentsResolver is released under the MIT License. See the bundled [LICENSE](LICENSE) file for details.
