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

    public function methodObjectSame($mixed1, \stdClass $object1, $mixed2, \stdClass $object2)
    {
    }

    public function methodObjectHierarchy($mixed1, \Exception $object1, $mixed2, \RuntimeException $object2)
    {
    }

    public function methodObjectHierarchyReverse($mixed1, \RuntimeException $object1, $mixed2, \Exception $object2)
    {
    }

    public function methodOptional($mixed1, $mixed2, $optional1 = 1, $optional2 = 2)
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

    public static function staticMethodObjectSame($mixed1, \stdClass $object1, $mixed2, \stdClass $object2)
    {
    }

    public static function staticMethodObjectHierarchy($mixed1, \Exception $object1, $mixed2, \RuntimeException $object2)
    {
    }

    public static function staticMethodObjectHierarchyReverse($mixed1, \RuntimeException $object1, $mixed2, \Exception $object2)
    {
    }

    public static function staticMethodOptional($mixed1, $mixed2, $optional1 = 1, $optional2 = 2)
    {
    }
}
