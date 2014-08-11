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

/**
 * Sorts parameters.
 *
 * @param \ReflectionParameter $a
 * @param \ReflectionParameter $b
 *
 * @return int
 */
function sort_parameters(\ReflectionParameter $a, \ReflectionParameter $b)
{
    if ($a->isOptional() ^ $b->isOptional()) {
        return (($a->isOptional() > $b->isOptional()) << 1) - 1;
    }
    if ($a->getClass() && !$b->getClass()) {
        return -1;
    }
    if (!$a->getClass() && $b->getClass()) {
        return 1;
    }
    if ($a->isArray() ^ $b->isArray()) {
        return (($a->isArray() < $b->isArray()) << 1) - 1;
    }
    if ($a->isCallable() ^ $b->isCallable()) {
        return (($a->isCallable() < $b->isCallable()) << 1) - 1;
    }

    return $a->getPosition() - $b->getPosition();
}
