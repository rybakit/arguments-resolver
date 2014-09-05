<?php

namespace CallableArgumentsResolver;

use CallableArgumentsResolver\Adapter\Adapter;

class CallableArgumentsResolver
{
    /**
     * @var callable
     */
    private $callable;

    /**
     * @var Adapter
     */
    private $adapter;

    /**
     * @var ArgumentsResolver
     */
    private $resolver;

    public function __construct(callable $callable, Adapter $adapter = null)
    {
        $this->callable = $callable;
        $this->adapter = $adapter;
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
        return $this->getResolver()->resolveArguments($parameters);
    }

    /**
     * @return ArgumentsResolver
     */
    private function getResolver()
    {
        if (!$this->resolver) {
            $this->resolver = create_resolver($this->callable, $this->adapter);
        }

        return $this->resolver;
    }
}
