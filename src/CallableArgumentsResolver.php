<?php

class CallableArgumentsResolver
{
    private $callable;
    private $reflection;

    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * @param array $parameters
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    public function getArguments(array $parameters)
    {
        $reflection = $this->getReflection();

        if (count($parameters) < $reflection->getNumberOfRequiredParameters()) {
            throw new \InvalidArgumentException('Not enough parameters provided.');
        }

        $arguments = [];

        foreach ($reflection->getParameters() as $parameter) {
            if (empty($parameters)) {
                break;
            }

            $name = $parameter->name;

            if (array_key_exists($name, $parameters)) {
                $value = $parameters[$name];
                unset($parameters[$name]);
            } else {
                $value = array_shift($parameters);
            }

            static::assertArgument($parameter, $value);

            $arguments[] = $value;
        }

        return $arguments;
    }

    protected function getReflection()
    {
        if (!$this->reflection) {
            $this->reflection = $this->createReflection();
        }

        return $this->reflection;
    }

    protected function createReflection()
    {
        if (is_array($this->callable)) {
            return new \ReflectionMethod($this->callable[0], $this->callable[1]);
        }

        if (is_object($this->callable) && !$this->callable instanceof \Closure) {
            return (new \ReflectionObject($this->callable))->getMethod('__invoke');
        }

        return new \ReflectionFunction($this->callable);
    }

    protected static function assertArgument(\ReflectionParameter $parameter, $value)
    {
        if ($parameter->isArray() && !is_array($value)) {
            throw new InvalidArgumentTypeException('array', $parameter, $value);
        }

        if ($parameter->isCallable() && !is_callable($value)) {
            throw new InvalidArgumentTypeException('callable', $parameter, $value);
        }

        if (!$refClass = $parameter->getClass()) {
            return;
        }

        if (!is_object($value) || !$refClass->isInstance($value)) {
            throw new InvalidArgumentTypeException($refClass->name, $parameter, $value);
        }
    }
}
