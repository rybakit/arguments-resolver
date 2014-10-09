<?php

namespace ArgumentsResolver;

abstract class ArgumentsResolver
{
    /**
     * @var \ReflectionFunctionAbstract
     */
    protected $reflection;

    public function __construct(\ReflectionFunctionAbstract $reflection)
    {
        $this->reflection = $reflection;
    }

    /**
     * Resolves function arguments.
     *
     * @param array $parameters
     *
     * @return array
     *
     * @throws UnresolvableArgumentException
     */
    public function resolveArguments(array $parameters)
    {
        if (!$number = $this->reflection->getNumberOfParameters()) {
            return [];
        }

        $arguments = array_fill(0, $number, null);

        foreach ($this->getParameters() as $pos => $parameter) {
            $result = $this->resolve($parameter, $parameters);

            if ($result) {
                $arguments[$pos] = $result[1];
                unset($parameters[$result[0]]);
                continue;
            }

            if ($parameter->isDefaultValueAvailable()) {
                $arguments[$pos] = $parameter->getDefaultValue();
                continue;
            }

            throw new UnresolvableArgumentException($parameter);
        }

        return $arguments;
    }

    /**
     * Returns an array of reflection parameters.
     *
     * @return \ReflectionParameter[]
     */
    protected function getParameters()
    {
        return $this->reflection->getParameters();
    }

    /**
     * Returns the [key, value] pair if the parameter is resolved or false otherwise.
     *
     * @param \ReflectionParameter $parameter
     * @param array                $parameters
     *
     * @return array|bool
     */
    abstract protected function resolve(\ReflectionParameter $parameter, array $parameters);
}
