<?php

namespace CallableArgumentsResolver\Tests;

use CallableArgumentsResolver as f;
use CallableArgumentsResolver\Adapter\Adapter;

class FunctionsTest extends \PHPUnit_Framework_TestCase
{
    use ResolvingTrait;

    protected function resolveArguments(array $arguments, $type, $mode, Adapter $adapter)
    {
        $callable = create_callable($type, $mode);

        return f\resolve_arguments($callable, $arguments, $adapter);
    }
}
