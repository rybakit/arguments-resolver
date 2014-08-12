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
            throw new \InvalidArgumentException(sprintf('Not enough parameters are provided for %s.', $this->getName()));
        }

        $arguments = array_fill(0, $num, null);

        foreach ($this->getParameters() as $pos => $parameter) {
            $key = $parameter->findKey($parameters);

            if (null !== $key) {
                $arguments[$pos] = $parameters[$key];
                unset($parameters[$key]);
                continue;
            }

            if ($parameter->hasDefaultValue()) {
                $arguments[$pos] = $parameter->getDefaultValue();
                continue;
            }

            throw new \InvalidArgumentException(sprintf('Unable to resolve argument %s of %s.', $parameter->getName(), $this->getName()));
        }

        return $arguments;
    }

    /**
     * Returns the callable name.
     *
     * @return string
     */
    public function getName()
    {
        if ($this->reflection instanceof \ReflectionFunction) {
            return $this->reflection->name;
        }

        return sprintf('%s::%s', $this->reflection->getDeclaringClass()->name, $this->reflection->name);
    }

    /**
     * Returns a generator of sorted parameters.
     *
     * @return \Generator|ParameterReflection[]
     */
    protected function getParameters()
    {
        if (null === $this->parameters) {
            $this->parameters = $this->reflection->getParameters();
            uasort($this->parameters, __CLASS__.'::sortParameters');
        }

        foreach ($this->parameters as $pos => $parameter) {
            yield $pos => new ParameterReflection($parameter);
        }
    }

    /**
     * Sorts parameters by type and optionality.
     *
     * @param \ReflectionParameter $a
     * @param \ReflectionParameter $b
     *
     * @return int
     */
    protected static function sortParameters(\ReflectionParameter $a, \ReflectionParameter $b)
    {
        if ($a->isOptional() ^ $b->isOptional()) {
            return $a->isOptional() ? 1 : -1;
        }
        if (null !== $a->getClass() ^ null !== $b->getClass()) {
            return $a->getClass() ? -1 : 1;
        }
        if ($a->isArray() ^ $b->isArray()) {
            return $a->isArray() ? -1 : 1;
        }
        if ($a->isCallable() ^ $b->isCallable()) {
            return $a->isCallable() ? -1 : 1;
        }

        return $a->getPosition() - $b->getPosition();
    }
}
