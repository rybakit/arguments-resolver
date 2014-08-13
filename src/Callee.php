<?php

namespace CallableArgumentsResolver;

class Callee
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
     * Returns callable reflection.
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
        if (!$number = $this->reflection->getNumberOfParameters()) {
            return [];
        }

        $arguments = array_fill(0, $number, null);

        foreach ($this->getParameters() as $pos => $parameter) {
            $argument = new Argument($parameter);
            $key = $argument->findKey($parameters);

            if (null !== $key) {
                $arguments[$pos] = $parameters[$key];
                unset($parameters[$key]);
                continue;
            }

            if ($argument->hasDefaultValue()) {
                $arguments[$pos] = $argument->getDefaultValue();
                continue;
            }

            throw new \InvalidArgumentException(sprintf('Unable to resolve argument %s of %s.', $argument->getName(), $this->getName()));
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
        $name = $this->reflection->name.'()';

        if ($this->reflection instanceof \ReflectionMethod) {
            $name = $this->reflection->getDeclaringClass()->name.'::'.$name;
        }

        return $name;
    }

    /**
     * Returns an array of sorted parameters.
     *
     * @return \ReflectionParameter[]
     */
    protected function getParameters()
    {
        if (null === $this->parameters) {
            $this->parameters = $this->reflection->getParameters();
            uasort($this->parameters, [__CLASS__, 'sortParameters']);
        }

        return $this->parameters;
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
            return $a->isOptional() << 1 - 1;
        }

        if (null !== $a->getClass() ^ null !== $b->getClass()) {
            return $a->getClass() ? -1 : 1;
        }

        if ($a->isArray() ^ $b->isArray()) {
            return $b->isArray() << 1 - 1;
        }

        if ($a->isCallable() ^ $b->isCallable()) {
            return $b->isCallable() << 1 - 1;
        }

        return $a->getPosition() - $b->getPosition();
    }
}
