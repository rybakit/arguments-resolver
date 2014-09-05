<?php

namespace CallableArgumentsResolver\Adapter;

class KeyAdapter implements Adapter
{
    /**
     * {@inheritdoc}
     */
    public function prepare(array $parameters)
    {
        return $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(\ReflectionParameter $parameter, array $parameters)
    {
        if (array_key_exists($parameter->name, $parameters)) {
            return [$parameter->name, $parameters[$parameter->name]];
        }

        return false;
    }
}
