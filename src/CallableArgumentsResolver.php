<?php

namespace CallableArgumentsResolver;

class CallableArgumentsResolver
{
    /**
     * @var callable
     */
    private $callable;

    /**
     * @var CallableReflection
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
     * Resolves callable arguments.
     *
     * @param array $parameters
     *
     * @return array
     */
    public function resolveArguments(array $parameters)
    {
        return $this->getReflection()->resolveArguments($parameters);
    }

    /**
     * @return CallableReflection
     */
    protected function getReflection()
    {
        if (!$this->reflection) {
            $this->reflection = create_reflection($this->callable);
        }

        return $this->reflection;
    }
}
