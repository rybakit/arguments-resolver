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
 * @param ReflectionParameter $param
 * @param array               $params
 *
 * @return mixed
 */
function find_key(\ReflectionParameter $param, array $params)
{
    if (!$params) {
        return;
    }

    if (array_key_exists($param->name, $params)) {
        return $param->name;
    }

    reset($params);

    return key($params);
}

/**
 * @param ReflectionParameter $param
 * @param array               $params
 *
 * @return mixed
 */
function find_key_by_type(\ReflectionParameter $param, array $params)
{
    $found = null;

    foreach ($params as $key => $value) {
        if (!match_type($param, $value)) {
            continue;
        }

        if ($key === $param->name) {
            return $key;
        }

        if (!$found) {
            $found = $key;
        }
    }

    return $found;
}

/**
 * @param ReflectionParameter $param
 *
 * @return bool
 */
function has_type(\ReflectionParameter $param)
{
    return $param->getClass() || $param->isArray() || $param->isCallable();
}

/**
 * @param ReflectionParameter $param
 * @param mixed               $value
 *
 * @return bool
 */
function match_type(\ReflectionParameter $param, $value)
{
    $class = $param->getClass();

    if ($class && is_object($value) && $class->isInstance($value)) {
        return true;
    }

    if ($param->isArray() && is_array($value)) {
        return true;
    }

    if ($param->isCallable() && is_callable($value)) {
        return true;
    }

    return false;
}
