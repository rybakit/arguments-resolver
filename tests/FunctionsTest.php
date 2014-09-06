<?php

namespace ArgumentsResolver\Tests;

use ArgumentsResolver as ar;
use ArgumentsResolver\Adapter\Adapter;

class FunctionsTest extends \PHPUnit_Framework_TestCase
{
    use ResolvingTrait;

    protected function resolveArguments(array $arguments, $type, $mode, Adapter $adapter)
    {
        $callable = create_callable($type, $mode);

        return ar\resolve_arguments($callable, $arguments, $adapter);
    }
}
