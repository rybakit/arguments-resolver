<?php

namespace CallableArgumentsResolver\Tests;

/**
 * Creates a callable based on type and mode.
 *
 * @param string $type
 * @param string $mode
 *
 * @return callable
 *
 * @throws \InvalidArgumentException
 */
function create_callable($type, $mode)
{
    switch ($type) {
        case 'method':
            return [new TestClass(), 'method'.camelize($mode)];

        case 'static_method':
            return [__NAMESPACE__.'\TestClass', 'staticMethod'.camelize($mode)];

        case 'invoked_method':
            return (new \ReflectionClass(__NAMESPACE__.'\Invoke'.camelize($mode).'Class'))->newInstance();

        case 'closure':
            return (new \ReflectionFunction(__NAMESPACE__.'\function_'.$mode))->getClosure();

        case 'function':
            return __NAMESPACE__.'\function_'.$mode;
    }

    throw new \InvalidArgumentException(sprintf('Unsupported callable type "%s".', $type));
}

/**
 * Converts a string to UpperCamelCase.
 *
 * @param string $string
 *
 * @return string
 */
function camelize($string)
{
    return str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
}
