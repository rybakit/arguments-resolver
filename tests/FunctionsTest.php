<?php

namespace CallableArgumentsResolver\Tests;

use CallableArgumentsResolver as f;
use CallableArgumentsResolver\ArgumentMatcher\ArgumentMatcher;

class FunctionsTest extends \PHPUnit_Framework_TestCase
{
    use ResolvingTrait;

    protected function resolveArguments(array $arguments, $type, $mode, ArgumentMatcher $matcher)
    {
        $callable = create_callable($type, $mode);

        return f\resolve_arguments($callable, $arguments, $matcher);
    }
}
