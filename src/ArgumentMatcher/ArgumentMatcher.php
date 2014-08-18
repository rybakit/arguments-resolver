<?php

namespace CallableArgumentsResolver\ArgumentMatcher;

interface ArgumentMatcher
{
    /**
     * Returns a filtered array of the reflection parameters.
     *
     * @param \ReflectionParameter[] $parameters
     *
     * @return \ReflectionParameter[]
     */
    public function filter(array $parameters);

    /**
     * Returns the position of the first matched value in the parameters array
     * or false otherwise.
     *
     * @param \ReflectionParameter $parameter
     * @param array                $parameters
     *
     * @return mixed
     */
    public function match(\ReflectionParameter $parameter, array $parameters);
}
