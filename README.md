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
$controller = function ($name) { return 'Hello '.$name; };
$resolver = new CallableArgumentsResolver($controller);
$arguments = $resolver->getArguments($_GET);
```


## License

CallableArgumentsResolver is released under the MIT License. See the bundled [LICENSE](LICENSE) file for details.
