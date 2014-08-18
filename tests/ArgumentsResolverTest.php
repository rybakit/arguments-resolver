<?php

namespace CallableArgumentsResolver\Tests;

use CallableArgumentsResolver\ArgumentMatcher\InDepthArgumentMatcher;
use CallableArgumentsResolver\ArgumentsResolver;

class ArgumentsResolverTest extends \PHPUnit_Framework_TestCase
{
    use TestResolvingTrait;

    protected function resolveArguments(array $arguments, $type, $mode)
    {
        $reflection = create_callable_reflection($type, $mode);
        $callee = new ArgumentsResolver($reflection, new InDepthArgumentMatcher());

        return $callee->resolveArguments($arguments);
    }
}
