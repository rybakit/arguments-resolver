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

$informer = function ($username, DateTime $lastLoginDate, $greeting = 'Hello %s!') {
    printf($greeting, $username);
    printf("\nYour last login was on the %s.", $lastLoginDate->format('jS \of F Y'));
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
Your last login was on the 3rd of September 2013.
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
