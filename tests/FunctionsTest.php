<?php

namespace CallableArgumentsResolver\Tests;

use CallableArgumentsResolver as f;

class FunctionsTest extends \PHPUnit_Framework_TestCase
{
    use TestResolvingTrait;

    protected function resolveArguments(array $arguments, $type, $mode)
    {
        $callable = create_callable($type, $mode);

        return f\resolve_arguments($callable, $arguments);
    }
}
