<?php

namespace CallableArgumentsResolver\Tests;

use CallableArgumentsResolver\Adapter\Adapter;

trait InDepthResolvingTrait
{
    /**
     * @dataProvider provideCallableData
     */
    public function testInDepthResolvingVariousOrdered($callableType, Adapter $adapter)
    {
        $parameters = ['foo', new \stdClass(), ['baz'], 'qux'];

        $this->assertArguments($parameters, $parameters, $callableType, 'various', $adapter);
    }

    /**
     * @dataProvider provideCallableData
     */
    public function testInDepthResolvingVariousUnordered($callableType, Adapter $adapter)
    {
        $bar = new \stdClass();
        $baz = ['baz'];

        $parameters = ['foo', 'qux', $baz, $bar];
        $arguments = ['foo', $bar, $baz, 'qux'];

        $this->assertArguments($arguments, $parameters, $callableType, 'various', $adapter);
    }

    /**
     * @dataProvider provideCallableData
     */
    public function testInDepthResolvingVariousOptional($callableType, Adapter $adapter)
    {
        $parameters = ['foo', new \stdClass()];
        $arguments = array_merge($parameters, [[], null]);

        $this->assertArguments($arguments, $parameters, $callableType, 'various', $adapter);
    }

    /**
     * @dataProvider provideCallableData
     */
    public function testInDepthResolvingVariousByNameAndType($callableType, Adapter $adapter)
    {
        $foo = (object) ['name' => 'foo'];
        $bar = (object) ['name' => 'bar'];

        $parameters = ['bar' => $bar, $foo];
        $arguments = [$foo, $bar, [], null];

        $this->assertArguments($arguments, $parameters, $callableType, 'various', $adapter);
    }

    /**
     * @dataProvider provideCallableData
     */
    public function testInDepthResolvingObjectSameType($callableType, Adapter $adapter)
    {
        $bar = (object) ['name' => 'bar'];
        $qux = (object) ['name' => 'qux'];

        $parameters = [$bar, 'foo', $qux, 'baz'];
        $arguments = ['foo', $bar, 'baz', $qux];

        $this->assertArguments($arguments, $parameters, $callableType, 'object_same', $adapter);
    }

    /**
     * @dataProvider provideCallableData
     */
    public function testInDepthResolvingObjectHierarchyType($callableType, Adapter $adapter)
    {
        $bar = new \Exception();
        $qux = new \RuntimeException();

        $parameters = [$qux, 'foo', $bar, 'baz'];
        $arguments = ['foo', $bar, 'baz', $qux];

        $this->assertArguments($arguments, $parameters, $callableType, 'object_hierarchy', $adapter);
    }

    /**
     * @dataProvider provideCallableData
     */
    public function testInDepthResolvingObjectHierarchyTypeReverse($callableType, Adapter $adapter)
    {
        $bar = new \RuntimeException();
        $qux = new \Exception();

        $parameters = [$qux, 'foo', $bar, 'baz'];
        $arguments = ['foo', $bar, 'baz', $qux];

        $this->assertArguments($arguments, $parameters, $callableType, 'object_hierarchy_reverse', $adapter);
    }

    /**
     * @dataProvider provideCallableData
     */
    public function testInDepthResolvingCallable($callableType, Adapter $adapter)
    {
        $bar = function () {};

        $parameters = [$bar, 'foo', 'baz'];
        $arguments = ['foo', $bar, 'baz'];

        $this->assertArguments($arguments, $parameters, $callableType, 'callable', $adapter);
    }

    /**
     * @dataProvider provideCallableDataWithInvalidTypes
     * @expectedException \CallableArgumentsResolver\UnresolvableArgumentException
     * @expectedExceptionMessage Unable to resolve argument
     */
    public function testInDepthResolvingThrowsExceptionOnInvalidType($callableType, Adapter $adapter, $functionName, $parameters)
    {
        $this->resolveArguments($parameters, $callableType, $functionName, $adapter);
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

    abstract public function assertArguments(array $expected, array $actual, $type, $mode, Adapter $adapter);

    abstract protected function resolveArguments(array $arguments, $type, $mode, Adapter $adapter);
}
