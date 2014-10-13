<?php

namespace ArgumentsResolver\Tests;

use ArgumentsResolver\InDepthArgumentResolver;

class InDepthArgumentsResolverTest extends ArgumentsResolverTest
{
    /**
     * {@inheritdoc}
     */
    protected function createResolver(\ReflectionFunctionAbstract $function)
    {
        return new InDepthArgumentResolver($function);
    }

    public function testResolvingVariousOrdered()
    {
        $parameters = ['foo', new \stdClass(), ['baz'], 'qux'];
        $arguments = $parameters;

        $this->assertArguments($arguments, $parameters, 'various');
    }

    public function testResolvingVariousUnordered()
    {
        $bar = new \stdClass();
        $baz = ['baz'];

        $parameters = ['foo', 'qux', $baz, $bar];
        $arguments = ['foo', $bar, $baz, 'qux'];

        $this->assertArguments($arguments, $parameters, 'various');
    }

    public function testResolvingVariousOptional()
    {
        $parameters = ['foo', new \stdClass()];
        $arguments = array_merge($parameters, [[], null]);

        $this->assertArguments($arguments, $parameters, 'various');
    }

    public function testResolvingVariousByNameAndType()
    {
        $foo = (object) ['name' => 'foo'];
        $bar = (object) ['name' => 'bar'];

        $parameters = ['bar' => $bar, $foo];
        $arguments = [$foo, $bar, [], null];

        $this->assertArguments($arguments, $parameters, 'various');
    }

    public function testResolvingObjectSameType()
    {
        $bar = (object) ['name' => 'bar'];
        $qux = (object) ['name' => 'qux'];

        $parameters = [$bar, 'foo', $qux, 'baz'];
        $arguments = ['foo', $bar, 'baz', $qux];

        $this->assertArguments($arguments, $parameters, 'object_same');
    }

    public function testResolvingObjectHierarchyType()
    {
        $bar = new \Exception();
        $qux = new \RuntimeException();

        $parameters = [$qux, 'foo', $bar, 'baz'];
        $arguments = ['foo', $bar, 'baz', $qux];

        $this->assertArguments($arguments, $parameters, 'object_hierarchy');
    }

    public function testResolvingObjectHierarchyTypeReverse()
    {
        $bar = new \RuntimeException();
        $qux = new \Exception();

        $parameters = [$qux, 'foo', $bar, 'baz'];
        $arguments = ['foo', $bar, 'baz', $qux];

        $this->assertArguments($arguments, $parameters, 'object_hierarchy_reverse');
    }

    public function testResolvingCallable()
    {
        $bar = function () {};

        $parameters = [$bar, 'foo', 'baz'];
        $arguments = ['foo', $bar, 'baz'];

        $this->assertArguments($arguments, $parameters, 'callable');
    }

    /**
     * @dataProvider provideInvalidParameterTypes
     * @expectedException \ArgumentsResolver\UnresolvableArgumentException
     * @expectedExceptionMessage Unable to resolve argument
     */
    public function testResolvingThrowsExceptionOnInvalidType($testCase, $parameters)
    {
        $this->resolveArguments($parameters, $testCase);
    }

    public function provideInvalidParameterTypes()
    {
        return [
            ['array', [null, null, null]],
            ['callable', [null, null, null]],
            ['object_same', [null, null, null, null]],
        ];
    }
}
