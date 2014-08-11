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
    public function resolveArguments(array $parameters)
    {
        return resolve_reflection_arguments($this->getReflection(), $parameters);
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
