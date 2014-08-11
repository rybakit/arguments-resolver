<?php

namespace CallableArgumentsResolver;

class CallableReflection
{
    /**
     * @var \ReflectionFunctionAbstract
     */
    private $reflection;

    /**
     * @var \ReflectionParameter[]
     */
    private $parameters;

    public function __construct(\ReflectionFunctionAbstract $reflection)
    {
        $this->reflection = $reflection;
    }

    /**
     * Returns the reflection being wrapped.
     *
     * @return \ReflectionFunctionAbstract
     */
    public function getReflection()
    {
        return $this->reflection;
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
        if (!$num = $this->reflection->getNumberOfParameters()) {
            return [];
        }

        if (count($parameters) < $this->reflection->getNumberOfRequiredParameters()) {
            throw new \InvalidArgumentException(sprintf('Not enough parameters are provided for %s.', $this->getPrettyName()));
        }

        $arguments = array_fill(0, $num, null);

        foreach ($this->getParameters() as $parameter) {
            $key = $parameter->findKey($parameters);

            if (null !== $key) {
                $arguments[$parameter->getPosition()] = $parameters[$key];
                unset($parameters[$key]);
                continue;
            }

            if ($parameter->isDefaultValueAvailable()) {
                $arguments[$parameter->getPosition()] = $parameter->getDefaultValue();
                continue;
            }

            throw new \InvalidArgumentException(sprintf('Unable to resolve argument %s.', $parameter->getPrettyName()));
        }

        return $arguments;
    }

    /**
     * Returns a generator of sorted parameters ordered by a typehint and optionality.
     *
     * @return \Generator
     */
    public function getParameters()
    {
        if (null === $this->parameters) {
            $this->parameters = $this->reflection->getParameters();
            usort($this->parameters, __NAMESPACE__.'\sort_parameters');
        }

        foreach ($this->parameters as $parameter) {
            yield new ParameterReflection($parameter);
        }
    }

    public function getPrettyName()
    {
        if ($this->reflection instanceof \ReflectionFunction) {
            return $this->reflection->name;
        }

        return sprintf('%s::%s', $this->reflection->getDeclaringClass()->name, $this->reflection->name);
    }
}
