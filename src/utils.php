<?php

/**
 * @param callable $callable
 *
 * @return ReflectionFunction|ReflectionMethod
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
 * @param ReflectionParameter $a
 * @param ReflectionParameter $b
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

/**
 * @param array               $parameters
 * @param ReflectionParameter $parameter
 *
 * @return array
 */
function filter_by_type(array $parameters, \ReflectionParameter $parameter)
{
    $result = [];

    foreach ($parameters as $key => $value) {
        if (match_type($parameter, $value)) {
            $result[$key] = $value;
        }
    }

    return $result;
}

/**
 * @param ReflectionParameter $parameter
 *
 * @return bool
 */
function has_type(\ReflectionParameter $parameter)
{
    return $parameter->getClass() || $parameter->isArray() || $parameter->isCallable();
}

/**
 * @param ReflectionParameter $parameter
 * @param mixed               $value
 *
 * @return bool
 */
function match_type(\ReflectionParameter $parameter, $value)
{
    $class = $parameter->getClass();

    if ($class && is_object($value) && $class->isInstance($value)) {
        return true;
    }

    if ($parameter->isArray() && is_array($value)) {
        return true;
    }

    if ($parameter->isCallable() && is_callable($value)) {
        return true;
    }

    return false;
}
