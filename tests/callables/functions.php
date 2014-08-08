<?php

namespace CallableArgumentsResolver\Tests;

function function_without_arguments()
{
}

function function_with_various($foo, \stdClass $bar, array $baz = [], $qux = null)
{
}

function function_with_array($mixed1, array $array, $mixed2)
{
}

function function_with_callable($mixed1, callable $callable, $mixed2)
{
}

function function_with_object($mixed1, \stdClass $object, $mixed2)
{
}

function function_with_optional($mixed1, $mixed2, $optional1 = 1, $optional1 = 2)
{
}
