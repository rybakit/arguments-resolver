<?php

namespace CallableArgumentsResolver;

use CallableArgumentsResolver\ArgumentMatcher\ArgumentMatcher;

class CallableArgumentsResolver
{
    /**
     * @var callable
     */
    private $callable;

    /**
     * @var ArgumentMatcher
     */
    private $matcher;

    /**
     * @var ArgumentsResolver
     */
    private $resolver;

    public function __construct(callable $callable, ArgumentMatcher $matcher = null)
    {
        $this->callable = $callable;
        $this->matcher = $matcher;
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
            $this->resolver = create_resolver($this->callable, $this->matcher);
        }

        return $this->resolver;
    }
}
