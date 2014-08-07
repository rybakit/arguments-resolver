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
}
