<?php

namespace CallableArgumentsResolver;

use CallableArgumentsResolver\ArgumentMatcher\ArgumentMatcher;
use CallableArgumentsResolver\ArgumentMatcher\InDepthArgumentMatcher;

/**
 * Resolves callable arguments.
 *
 * @param callable             $callable
 * @param array                $parameters
 * @param ArgumentMatcher|null $matcher
 *
 * @return array
 */
function resolve_arguments(callable $callable, array $parameters, ArgumentMatcher $matcher = null)
{
    return create_resolver($callable, $matcher)->resolveArguments($parameters);
}

/**
 * Creates an arguments resolver.
 *
 * @param callable             $callable
 * @param ArgumentMatcher|null $matcher
 *
 * @return ArgumentsResolver
 */
function create_resolver(callable $callable, ArgumentMatcher $matcher = null)
{
    $reflection = create_callable_reflection($callable);

    return new ArgumentsResolver($reflection, $matcher ?: new InDepthArgumentMatcher());
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
