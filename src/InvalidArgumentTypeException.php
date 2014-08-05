<?php

class InvalidArgumentTypeException extends \RuntimeException
{
    private $parameter;

    public function __construct(\ReflectionParameter $parameter, $expectedType, $value, $code = null, \Exception $previous = null)
    {
        $argument = $parameter->name ?: '#'.$parameter->getPosition();
        $message = sprintf('Invalid type for argument %s. Expected "%s", but got "%s".', $argument, $expectedType, gettype($value));

        parent::__construct($message, $code, $previous);

        $this->parameter = $parameter;
    }

    public function getParameter()
    {
        return $this->parameter;
    }
}
