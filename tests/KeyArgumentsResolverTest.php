<?php

namespace ArgumentsResolver\Tests;

use ArgumentsResolver\KeyArgumentsResolver;

class KeyArgumentsResolverTest extends ArgumentsResolverTest
{
    /**
     * {@inheritdoc}
     */
    protected function createResolver(\ReflectionFunctionAbstract $function)
    {
        return new KeyArgumentsResolver($function);
    }
}
