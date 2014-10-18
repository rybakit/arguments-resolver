<?php

namespace ArgumentsResolver\Tests;

use ArgumentsResolver\NamedArgumentsResolver;

class NamedArgumentsResolverTest extends ArgumentsResolverTest
{
    /**
     * {@inheritdoc}
     */
    protected function createResolver(\ReflectionFunctionAbstract $function)
    {
        return new NamedArgumentsResolver($function);
    }
}
