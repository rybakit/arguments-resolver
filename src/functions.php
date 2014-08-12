<?php

namespace CallableArgumentsResolver;

/**
 * Resolves callable arguments.
 *
 * @param callable $callable
 * @param array    $parameters
 *
 * @return array
 */
function resolve_arguments(callable $callable, array $parameters)
{
    return create_reflection($callable)->resolveArguments($parameters);
}

/**
 * Creates a reflection for the callable.
 *
 * @param callable $callable
 *
 * @return CallableReflection
 */
function create_reflection(callable $callable)
{
    if (is_array($callable)) {
        $reflection = new \ReflectionMethod($callable[0], $callable[1]);
    } else if (is_object($callable) && !$callable instanceof \Closure) {
        $reflection = (new \ReflectionObject($callable))->getMethod('__invoke');
    } else {
        $reflection = new \ReflectionFunction($callable);
    }

    return new CallableReflection($reflection);
}
