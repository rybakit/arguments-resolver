<?php

namespace CallableArgumentsResolver;

use CallableArgumentsResolver\Adapter\Adapter;
use CallableArgumentsResolver\Adapter\InDepthAdapter;

/**
 * Resolves callable arguments.
 *
 * @param callable     $callable
 * @param array        $parameters
 * @param Adapter|null $adapter
 *
 * @return array
 */
function resolve_arguments(callable $callable, array $parameters, Adapter $adapter = null)
{
    return create_resolver($callable, $adapter)->resolveArguments($parameters);
}

/**
 * Creates an arguments resolver.
 *
 * @param callable     $callable
 * @param Adapter|null $adapter
 *
 * @return ArgumentsResolver
 */
function create_resolver(callable $callable, Adapter $adapter = null)
{
    $reflection = create_callable_reflection($callable);

    return new ArgumentsResolver($reflection, $adapter ?: new InDepthAdapter());
}

/**
 * Creates callable reflection.
 *
 * @param callable $callable
 *
 * @return \ReflectionFunction|\ReflectionMethod
 */
function create_callable_reflection(callable $callable)
{
    if (is_array($callable)) {
        return new \ReflectionMethod($callable[0], $callable[1]);
    }

    if (is_object($callable) && !$callable instanceof \Closure) {
        return (new \ReflectionObject($callable))->getMethod('__invoke');
    }

    return new \ReflectionFunction($callable);
}
