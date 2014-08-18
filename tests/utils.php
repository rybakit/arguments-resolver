<?php

namespace CallableArgumentsResolver\Tests;

/**
 * Creates a callable based on the callable type and mode.
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
        case CallableTypes::METHOD:
            return [new TestClass(), 'method'.camelize($mode)];

        case CallableTypes::STATIC_METHOD:
            return [__NAMESPACE__.'\TestClass', 'staticMethod'.camelize($mode)];

        case CallableTypes::INVOKED_METHOD:
            return (new \ReflectionClass(__NAMESPACE__.'\Invoke'.camelize($mode).'Class'))->newInstance();

        case CallableTypes::CLOSURE:
            return (new \ReflectionFunction(__NAMESPACE__.'\function_'.$mode))->getClosure();

        case CallableTypes::FUNC:
            return __NAMESPACE__.'\function_'.$mode;
    }

    throw new \InvalidArgumentException(sprintf('Unsupported callable type "%s".', $type));
}

/**
 * Creates a reflection object based on the callable type and mode.
 *
 * @param string $type
 * @param string $mode
 *
 * @return \ReflectionFunction|\ReflectionMethod
 *
 * @throws \InvalidArgumentException
 */
function create_callable_reflection($type, $mode)
{
    switch ($type) {
        case CallableTypes::METHOD:
        case CallableTypes::STATIC_METHOD:
            return new \ReflectionMethod(__NAMESPACE__.'\TestClass', 'staticMethod'.camelize($mode));

        case CallableTypes::INVOKED_METHOD:
            return new \ReflectionMethod(__NAMESPACE__.'\Invoke'.camelize($mode).'Class', '__invoke');

        case CallableTypes::CLOSURE:
        case CallableTypes::FUNC:
            return new \ReflectionFunction(__NAMESPACE__.'\function_'.$mode);
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
