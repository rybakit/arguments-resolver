<?php

namespace ArgumentsResolver\Tests;

use ArgumentsResolver\InDepthArgumentResolver;

class ArgumentsResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideFunctionTypes
     */
    public function testResolvingVariousByName($functionType)
    {
        $bar = new \stdClass();
        $baz = ['baz'];

        $parameters = ['qux' => 'qux', 'baz' => $baz, 'bar' => $bar, 'foo' => 'foo'];
        $arguments = ['foo', $bar, $baz, 'qux'];

        $this->assertArguments($arguments, $parameters, $functionType, 'various');
    }

    /**
     * @dataProvider provideFunctionTypes
     */
    public function testResolvingOptional($functionType)
    {
        $parameters = ['mixed1' => 'foo', 'mixed2' => 'bar'];
        $arguments = ['foo', 'bar', 1, 2];

        $this->assertArguments($arguments, $parameters, $functionType, 'optional');
    }

    /**
     * @dataProvider provideFunctionTypes
     */
    public function testResolvingEmpty($functionType)
    {
        $parameters = ['foo'];

        $this->assertArguments([], $parameters, $functionType, 'empty');
    }

    /**
     * @dataProvider provideFunctionTypes
     * @expectedException \ArgumentsResolver\UnresolvableArgumentException
     * @expectedExceptionMessage Unable to resolve argument
     */
    public function testResolvingThrowsExceptionOnEmptyParameters($functionType)
    {
        $this->resolveArguments([], $functionType, 'various');
    }

    /**
     * @dataProvider provideFunctionTypes
     */
    public function testInDepthResolvingVariousOrdered($functionType)
    {
        $parameters = ['foo', new \stdClass(), ['baz'], 'qux'];

        $this->assertArguments($parameters, $parameters, $functionType, 'various');
    }

    /**
     * @dataProvider provideFunctionTypes
     */
    public function testInDepthResolvingVariousUnordered($functionType)
    {
        $bar = new \stdClass();
        $baz = ['baz'];

        $parameters = ['foo', 'qux', $baz, $bar];
        $arguments = ['foo', $bar, $baz, 'qux'];

        $this->assertArguments($arguments, $parameters, $functionType, 'various');
    }

    /**
     * @dataProvider provideFunctionTypes
     */
    public function testInDepthResolvingVariousOptional($functionType)
    {
        $parameters = ['foo', new \stdClass()];
        $arguments = array_merge($parameters, [[], null]);

        $this->assertArguments($arguments, $parameters, $functionType, 'various');
    }

    /**
     * @dataProvider provideFunctionTypes
     */
    public function testInDepthResolvingVariousByNameAndType($functionType)
    {
        $foo = (object) ['name' => 'foo'];
        $bar = (object) ['name' => 'bar'];

        $parameters = ['bar' => $bar, $foo];
        $arguments = [$foo, $bar, [], null];

        $this->assertArguments($arguments, $parameters, $functionType, 'various');
    }

    /**
     * @dataProvider provideFunctionTypes
     */
    public function testInDepthResolvingObjectSameType($functionType)
    {
        $bar = (object) ['name' => 'bar'];
        $qux = (object) ['name' => 'qux'];

        $parameters = [$bar, 'foo', $qux, 'baz'];
        $arguments = ['foo', $bar, 'baz', $qux];

        $this->assertArguments($arguments, $parameters, $functionType, 'object_same');
    }

    /**
     * @dataProvider provideFunctionTypes
     */
    public function testInDepthResolvingObjectHierarchyType($functionType)
    {
        $bar = new \Exception();
        $qux = new \RuntimeException();

        $parameters = [$qux, 'foo', $bar, 'baz'];
        $arguments = ['foo', $bar, 'baz', $qux];

        $this->assertArguments($arguments, $parameters, $functionType, 'object_hierarchy');
    }

    /**
     * @dataProvider provideFunctionTypes
     */
    public function testInDepthResolvingObjectHierarchyTypeReverse($functionType)
    {
        $bar = new \RuntimeException();
        $qux = new \Exception();

        $parameters = [$qux, 'foo', $bar, 'baz'];
        $arguments = ['foo', $bar, 'baz', $qux];

        $this->assertArguments($arguments, $parameters, $functionType, 'object_hierarchy_reverse');
    }

    /**
     * @dataProvider provideFunctionTypes
     */
    public function testInDepthResolvingCallable($functionType)
    {
        $bar = function () {};

        $parameters = [$bar, 'foo', 'baz'];
        $arguments = ['foo', $bar, 'baz'];

        $this->assertArguments($arguments, $parameters, $functionType, 'callable');
    }

    /**
     * @dataProvider provideFunctionWithInvalidTypes
     * @expectedException \ArgumentsResolver\UnresolvableArgumentException
     * @expectedExceptionMessage Unable to resolve argument
     */
    public function testInDepthResolvingThrowsExceptionOnInvalidType($functionType, $functionName, $parameters)
    {
        $this->resolveArguments($parameters, $functionType, $functionName);
    }

    public function provideFunctionWithInvalidTypes()
    {
        $data = [];

        foreach ($this->provideFunctionTypes() as $item) {
            $data[] = [$item, 'array', [null, null, null]];
            $data[] = [$item, 'callable', [null, null, null]];
            $data[] = [$item, 'object_same', [null, null, null, null]];
        }

        return $data;
    }

    public function provideFunctionTypes()
    {
        $data = [];
        foreach (FunctionTypes::getAll() as $type => $val) {
            $data[] = [$val];
        }

        return $data;
    }

    public function assertArguments(array $expected, array $actual, $type, $mode)
    {
        $this->assertSame($expected, $this->resolveArguments($actual, $type, $mode));
    }

    protected function resolveArguments(array $arguments, $type, $mode)
    {
        $reflection = create_callable_reflection($type, $mode);
        $resolver = new InDepthArgumentResolver($reflection);

        return $resolver->resolveArguments($arguments);
    }
}
