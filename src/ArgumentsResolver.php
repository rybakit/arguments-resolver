<?php

namespace ArgumentsResolver;

use ArgumentsResolver\Adapter\Adapter;

class ArgumentsResolver
{
    /**
     * @var \ReflectionFunctionAbstract
     */
    private $reflection;

    /**
     * @var Adapter
     */
    private $adapter;

    /**
     * @var \ReflectionParameter[]
     */
    private $parameters;

    public function __construct(\ReflectionFunctionAbstract $reflection, Adapter $adapter)
    {
        $this->reflection = $reflection;
        $this->adapter = $adapter;
    }

    /**
     * Resolves callable arguments.
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
            $result = $this->adapter->resolve($parameter, $parameters);

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
    private function getParameters()
    {
        if (null === $this->parameters) {
            $this->parameters = $this->adapter->prepare($this->reflection->getParameters());
        }

        return $this->parameters;
    }
}
