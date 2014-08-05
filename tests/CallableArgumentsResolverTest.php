<?php

class CallableArgumentsResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideCallableData
     */
    public function testGetArguments(callable $callable, $values, $arguments)
    {
        $resolver = new CallableArgumentsResolver($callable);
        $this->assertEquals($arguments, $resolver->getArguments($values));
    }

    public function provideCallableData()
    {
        $object = new \stdClass();
        $testClass = new TestClass();

        return [
            [[$testClass, 'methodWithoutArguments'], [], []],
            [[$testClass, 'methodWithArguments'], [1, 2], [1, 2]],
            [[$testClass, 'methodWithArguments'], [1, 2, 3], [1, 2, 3]],
            [[$testClass, 'methodWithArguments'], [2, 'foo' => 1], [1, 2]],
            [[$testClass, 'methodWithArguments'], ['bar' => 2, 'foo' => 1], [1, 2]],
            [[$testClass, 'methodWithArguments'], [1, 2, 'baz' => 3], [1, 2, 3]],
            [[$testClass, 'methodWithArrayArgument'], [[1, 2]], [[1, 2]]],
            [[$testClass, 'methodWithCallableArgument'], ['rand'], ['rand']],
            [[$testClass, 'methodWithObjectArgument'], [$object], [$object]],

            [function () {}, [], []],
            [function ($foo, $bar, $baz = null) {}, [1, 2], [1, 2]],
            [function ($foo, $bar, $baz = null) {}, [1, 2, 3], [1, 2, 3]],
            [function ($foo, $bar, $baz = null) {}, [2, 'foo' => 1], [1, 2]],
            [function ($foo, $bar, $baz = null) {}, ['bar' => 2, 'foo' => 1], [1, 2]],
            [function ($foo, $bar, $baz = null) {}, [1, 2, 'baz' => 3], [1, 2, 3]],
            [function (array $array) {}, [[1, 2]], [[1, 2]]],
            [function (callable $callable) {}, ['ord'], ['ord']],
            [function (\stdClass $object) {}, [$object], [$object]],

            ['rand', [], []],
            ['fmod', [1, 2], [1, 2]],
            ['array_sum', [[1, 2]], [[1, 2]]],
            ['get_object_vars', [$object], [$object]],
        ];
    }

    /**
     * @dataProvider provideCallableDataWithInvalidTypes
     * @expectedException InvalidArgumentTypeException
     */
    public function testGetArgumentsThrowsExceptionOnInvalidType(callable $callable, $values)
    {
        $resolver = new CallableArgumentsResolver($callable);
        $resolver->getArguments($values);
    }

    public function provideCallableDataWithInvalidTypes()
    {
        $testClass = new TestClass();

        return [
            [[$testClass, 'methodWithArrayArgument'], [null]],
            [[$testClass, 'methodWithCallableArgument'], [null]],
            [[$testClass, 'methodWithObjectArgument'], [null]],

            [function (array $array) {}, [null]],
            [function (callable $callable) {}, [null]],
            [function (\stdClass $object) {}, [null]],

            // @see https://bugs.php.net/bug.php?id=67454
            //['array_sum', [null]],
            //['get_object_vars', [null]],
        ];
    }

    /**
     * @dataProvider provideCallableDataWithEmptyParameters
     * @expectedException InvalidArgumentException
     */
    public function testGetArgumentsThrowsExceptionOnEmptyParameters(callable $callable, $values)
    {
        $resolver = new CallableArgumentsResolver($callable);
        $resolver->getArguments($values);
    }

    public function provideCallableDataWithEmptyParameters()
    {
        $testClass = new TestClass();

        return [
            [[$testClass, 'methodWithArguments'], []],
            [function ($foo) {}, []],
            ['ord', []],
        ];
    }
}
