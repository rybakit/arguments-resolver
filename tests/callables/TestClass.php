<?php

class TestClass
{
    public function methodWithoutArguments()
    {
    }

    public function methodWithArguments($foo, \stdClass $bar, array $baz = [], $qux = null)
    {
    }

    public function methodWithArrayArgument(array $array)
    {
    }

    public function methodWithCallableArgument(callable $callable)
    {
    }

    public function methodWithObjectArgument(\stdClass $object)
    {
    }

    public static function staticMethodWithoutArguments()
    {
    }

    public static function staticMethodWithArguments($foo, \stdClass $bar, array $baz = [], $qux = null)
    {
    }

    public static function staticMethodWithArrayArgument(array $array)
    {
    }

    public static function staticMethodWithCallableArgument(callable $callable)
    {
    }

    public static function staticMethodWithObjectArgument(\stdClass $object)
    {
    }
}
