<?php

namespace CallableArgumentsResolver\ArgumentMatcher;

class KeyArgumentMatcher implements ArgumentMatcher
{
    /**
     * {@inheritdoc}
     */
    public function filter(array $parameters)
    {
        return $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function match(\ReflectionParameter $parameter, array $parameters)
    {
        return array_key_exists($parameter->name, $parameters)
            ? $parameter->name
            : null;
    }
}
