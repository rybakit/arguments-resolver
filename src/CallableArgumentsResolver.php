<?php

namespace CallableArgumentsResolver;

class CallableArgumentsResolver
{
    /**
     * @var callable
     */
    private $callable;

    /**
     * @var Callee
     */
    private $callee;

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
        return $this->getCallee()->resolveArguments($parameters);
    }

    /**
     * @return Callee
     */
    protected function getCallee()
    {
        if (!$this->callee) {
            $this->callee = create_callee($this->callable);
        }

        return $this->callee;
    }
}
