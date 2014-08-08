<?php

namespace CallableArgumentsResolver;

/**
 * Creates a reflection for the callable.
 *
 * @param callable $callable
 *
 * @return \ReflectionFunction|\ReflectionMethod
 */
function create_reflection(callable $callable)
{
    if (is_array($callable)) {
        return new \ReflectionMethod($callable[0], $callable[1]);
    }

    if (is_object($callable) && !$callable instanceof \Closure) {
        return (new \ReflectionObject($callable))->getMethod('__invoke');
    }

    return new \ReflectionFunction($callable);
}

/**
 * Returns a generator of sorted parameters ordered by typehint and optionality.
 *
 * @param \ReflectionFunctionAbstract $reflection
 *
 * @return \Generator
 */
function get_parameters(\ReflectionFunctionAbstract $reflection)
{
    $parameters = $reflection->getParameters();
    usort($parameters, 'CallableArgumentsResolver\\sort_parameters');

    foreach ($parameters as $parameter) {
        yield new ReflectionParameterWrapper($parameter);
    }
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
