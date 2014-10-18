<?php

namespace ArgumentsResolver;

class NamedArgumentsResolver extends ArgumentsResolver
{
    /**
     * {@inheritdoc}
     */
    protected function match(\ReflectionParameter $parameter, array $parameters)
    {
        if (array_key_exists($parameter->name, $parameters)) {
            return [$parameter->name, $parameters[$parameter->name]];
        }

        return false;
    }
}
