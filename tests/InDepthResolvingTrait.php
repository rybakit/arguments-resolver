<?php

namespace CallableArgumentsResolver\Tests;

use CallableArgumentsResolver\ArgumentMatcher\ArgumentMatcher;

trait InDepthResolvingTrait
{
    /**
     * @dataProvider provideCallableData
     */
    public function testInDepthResolvingVariousOrdered($callableType, ArgumentMatcher $matcher)
    {
        $parameters = ['foo', new \stdClass(), ['baz'], 'qux'];

        $this->assertArguments($parameters, $parameters, $callableType, 'various', $matcher);
    }

    /**
     * @dataProvider provideCallableData
     */
    public function testInDepthResolvingVariousUnordered($callableType, ArgumentMatcher $matcher)
    {
        $bar = new \stdClass();
        $baz = ['baz'];

        $parameters = ['foo', 'qux', $baz, $bar];
        $arguments = ['foo', $bar, $baz, 'qux'];

        $this->assertArguments($arguments, $parameters, $callableType, 'various', $matcher);
    }

    /**
     * @dataProvider provideCallableData
     */
    public function testInDepthResolvingVariousOptional($callableType, ArgumentMatcher $matcher)
    {
        $parameters = ['foo', new \stdClass()];
        $arguments = array_merge($parameters, [[], null]);

        $this->assertArguments($arguments, $parameters, $callableType, 'various', $matcher);
    }

    /**
     * @dataProvider provideCallableData
     */
    public function testInDepthResolvingVariousByNameAndType($callableType, ArgumentMatcher $matcher)
    {
        $foo = (object) ['name' => 'foo'];
        $bar = (object) ['name' => 'bar'];

        $parameters = ['bar' => $bar, $foo];
        $arguments = [$foo, $bar, [], null];

        $this->assertArguments($arguments, $parameters, $callableType, 'various', $matcher);
    }

    /**
     * @dataProvider provideCallableData
     */
    public function testInDepthResolvingOptional($callableType, ArgumentMatcher $matcher)
    {
        $parameters = ['foo', 'bar'];
        $arguments = array_merge($parameters, [1, 2]);

        $this->assertArguments($arguments, $parameters, $callableType, 'optional', $matcher);
    }


    /**
     * @dataProvider provideCallableData
     */
    public function testInDepthResolvingObjectSameType($callableType, ArgumentMatcher $matcher)
    {
        $bar = (object) ['name' => 'bar'];
        $qux = (object) ['name' => 'qux'];

        $parameters = [$bar, 'foo', $qux, 'baz'];
        $arguments = ['foo', $bar, 'baz', $qux];

        $this->assertArguments($arguments, $parameters, $callableType, 'object_same', $matcher);
    }

    /**
     * @dataProvider provideCallableData
     */
    public function testInDepthResolvingObjectHierarchyType($callableType, ArgumentMatcher $matcher)
    {
        $bar = new \Exception();
        $qux = new \RuntimeException();

        $parameters = [$qux, 'foo', $bar, 'baz'];
        $arguments = ['foo', $bar, 'baz', $qux];

        $this->assertArguments($arguments, $parameters, $callableType, 'object_hierarchy', $matcher);
    }

    /**
     * @dataProvider provideCallableData
     */
    public function testInDepthResolvingObjectHierarchyTypeReverse($callableType, ArgumentMatcher $matcher)
    {
        $bar = new \RuntimeException();
        $qux = new \Exception();

        $parameters = [$qux, 'foo', $bar, 'baz'];
        $arguments = ['foo', $bar, 'baz', $qux];

        $this->assertArguments($arguments, $parameters, $callableType, 'object_hierarchy_reverse', $matcher);
    }

    /**
     * @dataProvider provideCallableData
     */
    public function testInDepthResolvingCallable($callableType, ArgumentMatcher $matcher)
    {
        $bar = function () {};

        $parameters = [$bar, 'foo', 'baz'];
        $arguments = ['foo', $bar, 'baz'];

        $this->assertArguments($arguments, $parameters, $callableType, 'callable', $matcher);
    }

    /**
     * @dataProvider provideCallableDataWithInvalidTypes
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unable to resolve argument
     */
    public function testInDepthResolvingThrowsExceptionOnInvalidType($callableType, ArgumentMatcher $matcher, $functionName, $parameters)
    {
        $this->resolveArguments($parameters, $callableType, $functionName, $matcher);
    }

    public function provideCallableDataWithInvalidTypes($testMethodName)
    {
        $data = [];

        foreach ($this->provideCallableData($testMethodName) as $item) {
            $data[] = [$item[0], $item[1], 'array', [null, null, null]];
            $data[] = [$item[0], $item[1], 'callable', [null, null, null]];
            $data[] = [$item[0], $item[1], 'object_same', [null, null, null, null]];
        }

        return $data;
    }

    abstract public function provideCallableData($testMethodName);

    abstract public function assertArguments(array $expected, array $actual, $type, $mode, ArgumentMatcher $matcher);

    abstract protected function resolveArguments(array $arguments, $type, $mode, ArgumentMatcher $matcher);
}
