<?php

namespace CallableArgumentsResolver\Tests;

class TestClass
{
    public function methodWithoutArguments()
    {
    }

    public function methodWithVarious($foo, \stdClass $bar, array $baz = [], $qux = null)
    {
    }

    public function methodWithArray($mixed1, array $array, $mixed2)
    {
    }

    public function methodWithCallable($mixed1, callable $callable, $mixed2)
    {
    }

    public function methodWithObject($mixed1, \stdClass $object, $mixed2)
    {
    }

    public function methodWithOptional($mixed1, $mixed2, $optional1 = 1, $optional1 = 2)
    {
    }

    public static function staticMethodWithoutArguments()
    {
    }

    public static function staticMethodWithVarious($foo, \stdClass $bar, array $baz = [], $qux = null)
    {
    }

    public static function staticMethodWithArray($mixed1, array $array, $mixed2)
    {
    }

    public static function staticMethodWithCallable($mixed1, callable $callable, $mixed2)
    {
    }

    public static function staticMethodWithObject($mixed1, \stdClass $object, $mixed2)
    {
    }

    public static function staticMethodWithOptional($mixed1, $mixed2, $optional1 = 1, $optional1 = 2)
    {
    }
}
