<?php

namespace CallableArgumentsResolver\Tests;

use CallableArgumentsResolver\Adapter\Adapter;
use CallableArgumentsResolver\ArgumentsResolver;

class ArgumentsResolverTest extends \PHPUnit_Framework_TestCase
{
    use ResolvingTrait;

    protected function resolveArguments(array $arguments, $type, $mode, Adapter $adapter)
    {
        $reflection = create_callable_reflection($type, $mode);
        $callee = new ArgumentsResolver($reflection, $adapter);

        return $callee->resolveArguments($arguments);
    }
}
