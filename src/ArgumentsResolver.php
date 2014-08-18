<?php

namespace CallableArgumentsResolver;

use CallableArgumentsResolver\ArgumentMatcher\ArgumentMatcher;

class ArgumentsResolver
{
    /**
     * @var \ReflectionFunctionAbstract
     */
    private $reflection;

    /**
     * @var ArgumentMatcher
     */
    private $matcher;

    /**
     * @var \ReflectionParameter[]
     */
    private $parameters;

    public function __construct(\ReflectionFunctionAbstract $reflection, ArgumentMatcher $matcher)
    {
        $this->reflection = $reflection;
        $this->matcher = $matcher;
    }

    /**
     * Resolves callable arguments.
     *
     * @param array $parameters
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    public function resolveArguments(array $parameters)
    {
        if (!$number = $this->reflection->getNumberOfParameters()) {
            return [];
        }

        $arguments = array_fill(0, $number, null);

        foreach ($this->getParameters() as $pos => $parameter) {
            $key = $this->matcher->match($parameter, $parameters);

            if (null !== $key) {
                $arguments[$pos] = $parameters[$key];
                unset($parameters[$key]);
                continue;
            }

            if ($parameter->isDefaultValueAvailable()) {
                $arguments[$pos] = $parameter->getDefaultValue();
                continue;
            }

            throw new \InvalidArgumentException(sprintf(
                'Unable to resolve argument $%s (#%d) of %s.',
                $parameter->name,
                $parameter->getPosition(),
                $this->getCallableName()
            ));
        }

        return $arguments;
    }

    /**
     * Returns an array of reflection parameters.
     *
     * @return \ReflectionParameter[]
     */
    private function getParameters()
    {
        if (null === $this->parameters) {
            $this->parameters = $this->matcher->filter($this->reflection->getParameters());
        }

        return $this->parameters;
    }

    /**
     * Returns the callable name.
     *
     * @return string
     */
    private function getCallableName()
    {
        $name = $this->reflection->name.'()';

        if ($this->reflection instanceof \ReflectionMethod) {
            $name = $this->reflection->getDeclaringClass()->name.'::'.$name;
        }

        return $name;
    }
}
