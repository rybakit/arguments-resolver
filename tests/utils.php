<?php

namespace ArgumentsResolver\Tests;

use ArgumentsResolver\Tests\Fixtures\TestClass;

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
        case FunctionTypes::METHOD:
            return [new TestClass(), 'method'.camelize($mode)];

        case FunctionTypes::STATIC_METHOD:
            return [__NAMESPACE__.'\Fixtures\TestClass', 'staticMethod'.camelize($mode)];

        case FunctionTypes::INVOKED_METHOD:
            return (new \ReflectionClass(__NAMESPACE__.'\Fixtures\Invoke'.camelize($mode).'Class'))->newInstance();

        case FunctionTypes::CLOSURE:
            return (new \ReflectionFunction(__NAMESPACE__.'\Fixtures\function_'.$mode))->getClosure();

        case FunctionTypes::FUNC:
            return __NAMESPACE__.'\function_'.$mode;
    }

    throw new \InvalidArgumentException(sprintf('Unsupported function type "%s".', $type));
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
        case FunctionTypes::METHOD:
        case FunctionTypes::STATIC_METHOD:
            return new \ReflectionMethod(__NAMESPACE__.'\Fixtures\TestClass', 'staticMethod'.camelize($mode));

        case FunctionTypes::INVOKED_METHOD:
            return new \ReflectionMethod(__NAMESPACE__.'\Fixtures\Invoke'.camelize($mode).'Class', '__invoke');

        case FunctionTypes::CLOSURE:
        case FunctionTypes::FUNC:
            return new \ReflectionFunction(__NAMESPACE__.'\Fixtures\function_'.$mode);
    }

    throw new \InvalidArgumentException(sprintf('Unsupported function type "%s".', $type));
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
