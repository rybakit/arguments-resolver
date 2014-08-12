<?php

namespace CallableArgumentsResolver\Tests;

class TestClass
{
    public function methodEmpty()
    {
    }

    public function methodVarious($foo, \stdClass $bar, array $baz = [], $qux = null)
    {
    }

    public function methodArray($mixed1, array $array, $mixed2)
    {
    }

    public function methodCallable($mixed1, callable $callable, $mixed2)
    {
    }

    public function methodObject($mixed1, \stdClass $object, $mixed2)
    {
    }

    public function methodOptional($mixed1, $mixed2, $optional1 = 1, $optional1 = 2)
    {
    }

    public static function staticMethodEmpty()
    {
    }

    public static function staticMethodVarious($foo, \stdClass $bar, array $baz = [], $qux = null)
    {
    }

    public static function staticMethodArray($mixed1, array $array, $mixed2)
    {
    }

    public static function staticMethodCallable($mixed1, callable $callable, $mixed2)
    {
    }

    public static function staticMethodObject($mixed1, \stdClass $object, $mixed2)
    {
    }

    public static function staticMethodOptional($mixed1, $mixed2, $optional1 = 1, $optional1 = 2)
    {
    }
}
