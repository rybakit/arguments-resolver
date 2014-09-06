<?php

namespace ArgumentsResolver;

class UnresolvableArgumentException extends \InvalidArgumentException
{
    public function __construct(\ReflectionParameter $parameter, $message = null, $code = null, \Exception $previous = null)
    {
        if (null === $message) {
            $message = sprintf(
                'Unable to resolve argument $%s (#%d) of %s.',
                $parameter->name,
                $parameter->getPosition(),
                static::getFunctionName($parameter->getDeclaringFunction())
            );
        }

        parent::__construct($message, $code, $previous);
    }

    /**
     * @param \ReflectionFunctionAbstract $reflection
     *
     * @return string
     */
    protected static function getFunctionName(\ReflectionFunctionAbstract $reflection)
    {
        $name = $reflection->name.'()';

        if ($reflection instanceof \ReflectionMethod) {
            $name = $reflection->getDeclaringClass()->name.'::'.$name;
        }

        return $name;
    }
}
