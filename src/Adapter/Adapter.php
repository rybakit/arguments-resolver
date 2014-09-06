<?php

namespace ArgumentsResolver\Adapter;

interface Adapter
{
    /**
     * Returns a filtered array of the reflection parameters.
     *
     * @param \ReflectionParameter[] $parameters
     *
     * @return \ReflectionParameter[]
     */
    public function prepare(array $parameters);

    /**
     * Returns the [key, value] pair if the parameter is resolved or false otherwise.
     *
     * @param \ReflectionParameter $parameter
     * @param array                $parameters
     *
     * @return array|bool
     */
    public function resolve(\ReflectionParameter $parameter, array $parameters);
}
