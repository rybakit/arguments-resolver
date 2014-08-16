<?php

namespace CallableArgumentsResolver\ArgumentMatcher;

interface ArgumentMatcher
{
    /**
     * Filters parameters.
     *
     * @param \ReflectionParameter[] $parameters
     *
     * @return \ReflectionParameter[]
     */
    public function filter(array $parameters);

    /**
     * Returns a position of the first matched value or null otherwise.
     *
     * @param \ReflectionParameter $parameter
     * @param array                $parameters
     *
     * @return mixed
     */
    public function match(\ReflectionParameter $parameter, array $parameters);
}
