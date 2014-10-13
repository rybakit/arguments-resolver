<?php

namespace ArgumentsResolver;

class Utils
{
    /**
     * Creates a reflection for a given function.
     *
     * @param mixed $function
     *
     * @return \ReflectionFunction|\ReflectionMethod
     */
    public static function createReflection($function)
    {
        if (is_string($function)) {
            $function = explode('::', $function, 2);

            if (1 === count($function)) {
                return new \ReflectionFunction($function[0]);
            }
        }

        if (is_array($function) && 2 === count($function)) {
            return new \ReflectionMethod($function[0], $function[1]);
        }

        if (is_object($function) && !$function instanceof \Closure) {
            return (new \ReflectionObject($function))->getMethod('__invoke');
        }

        return new \ReflectionFunction($function);
    }
}
