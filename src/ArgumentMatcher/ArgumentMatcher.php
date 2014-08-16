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
     * Returns the position of the first matched value in the parameters array
     * or null otherwise.
     *
     * @param \ReflectionParameter $parameter
     * @param array                $parameters
     *
     * @return mixed
     */
    public function match(\ReflectionParameter $parameter, array $parameters);
}
