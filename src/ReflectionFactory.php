<?php

namespace ArgumentsResolver;

abstract class ReflectionFactory
{
    /**
     * Creates a reflection for a given function.
     *
     * @param mixed $function
     *
     * @return \ReflectionFunction|\ReflectionMethod
     */
    public static function create($function)
    {
        if (is_string($function)) {
            return strpos($function, '::') ? new \ReflectionMethod($function) : new \ReflectionFunction($function);
        }

        if (is_array($function)) {
            return (new \ReflectionClass('ReflectionMethod'))->newInstanceArgs($function);
        }

        if (method_exists($function, '__invoke')) {
            return new \ReflectionMethod($function, '__invoke');
        }

        return new \ReflectionFunction($function);
    }
}
