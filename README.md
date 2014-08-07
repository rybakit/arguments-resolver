CallableArgumentsResolver
=========================
[![Build Status](https://secure.travis-ci.org/rybakit/callable-arguments-resolver.png?branch=master)](http://travis-ci.org/rybakit/callable-arguments-resolver)

CallableArgumentsResolver allows you to determine the arguments to pass to the callable.


## Installation

The recommended way to install CallableArgumentsResolver is through [Composer](http://getcomposer.org):

```sh
$ composer require rybakit/callable-arguments-resolver:~1.0@dev
```


## Usage example

```php
$informer = function ($username, Request $request, $greeting = 'Hello, %s!') {
    printf($greeting, $user->getName());
    printf("\nYour IP address is %s.", $request->getClientIp());
};

$parameters = [
    Request::createFromGlobals(),
    'Welcome, %!'
    ['unused parameter'],
    'username' => 'Stranger',
];

$resolver = new CallableArgumentsResolver($informer);

call_user_func_array(
    $resolver->getCallable(),
    $resolver->resolve($parameters)
);
```


## License

CallableArgumentsResolver is released under the MIT License. See the bundled [LICENSE](LICENSE) file for details.
