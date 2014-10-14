<?php

namespace ArgumentsResolver\Tests\Fixtures;

function function_empty()
{
}

function function_various($foo, \stdClass $bar, array $baz = [], $qux = null)
{
}

function function_array($mixed1, array $array, $mixed2)
{
}

function function_callable($mixed1, callable $callable, $mixed2)
{
}

function function_object_same($mixed1, \stdClass $object1, $mixed2, \stdClass $object2)
{
}

function function_object_hierarchy($mixed1, \Exception $object1, $mixed2, \RuntimeException $object2)
{
}

function function_object_hierarchy_reverse($mixed1, \RuntimeException $object1, $mixed2, \Exception $object2)
{
}

function function_optional($mixed1, $mixed2, $optional1 = 1, $optional2 = 2)
{
}
