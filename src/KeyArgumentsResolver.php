<?php

namespace ArgumentsResolver;

class KeyArgumentsResolver extends ArgumentsResolver
{
    /**
     * {@inheritdoc}
     */
    protected function resolve(\ReflectionParameter $parameter, array $parameters)
    {
        if (array_key_exists($parameter->name, $parameters)) {
            return [$parameter->name, $parameters[$parameter->name]];
        }

        return false;
    }
}
