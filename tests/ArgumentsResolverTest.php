<?php

namespace CallableArgumentsResolver\Tests;

use CallableArgumentsResolver\ArgumentMatcher\ArgumentMatcher;
use CallableArgumentsResolver\ArgumentsResolver;

class ArgumentsResolverTest extends \PHPUnit_Framework_TestCase
{
    use ResolvingTrait;

    protected function resolveArguments(array $arguments, $type, $mode, ArgumentMatcher $matcher)
    {
        $reflection = create_callable_reflection($type, $mode);
        $callee = new ArgumentsResolver($reflection, $matcher);

        return $callee->resolveArguments($arguments);
    }
}
