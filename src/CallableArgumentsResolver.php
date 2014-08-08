<?php

namespace CallableArgumentsResolver;

class CallableArgumentsResolver
{
    /**
     * @var callable
     */
    private $callable;

    /**
     * @var \ReflectionFunctionAbstract
     */
    private $reflection;

    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * Returns the callable.
     *
     * @return callable
     */
    public function getCallable()
    {
        return $this->callable;
    }

    /**
     * Returns an array of arguments.
     *
     * @param array $parameters
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    public function resolve(array $parameters)
    {
        $reflection = $this->getReflection();

        if (count($parameters) < $reflection->getNumberOfRequiredParameters()) {
            throw new \InvalidArgumentException('Not enough parameters are provided.');
        }

        $arguments = [];
        foreach (get_parameters($reflection) as $parameter) {
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

        ksort($arguments);

        return $arguments;
    }

    /**
     * @return \ReflectionFunctionAbstract
     */
    protected function getReflection()
    {
        if (!$this->reflection) {
            $this->reflection = create_reflection($this->callable);
        }

        return $this->reflection;
    }
}
