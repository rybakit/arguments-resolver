<?php

class InvalidArgumentTypeException extends \RuntimeException
{
    public function __construct($expectedType, \ReflectionParameter $parameter, $value, $code = null, \Exception $previous = null)
    {
        $argument = $parameter->name ?: '#'.$parameter->getPosition();
        $message = sprintf('Invalid type for argument %s. Expected "%s", but got "%s".', $argument, $expectedType, gettype($value));

        parent::__construct($message, $code, $previous);
    }
}
