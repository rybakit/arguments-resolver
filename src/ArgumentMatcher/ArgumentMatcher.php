<?php

namespace CallableArgumentsResolver\ArgumentMatcher;

interface ArgumentMatcher
{
    /**
     * @param \ReflectionParameter[] $parameters
     *
     * @return \ReflectionParameter[]
     */
    public function filter(array $parameters);

    /**
     * @param \ReflectionParameter $parameter
     * @param array                $parameters
     *
     * @return mixed
     */
    public function match(\ReflectionParameter $parameter, array $parameters);
}
