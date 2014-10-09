<?php

namespace ArgumentsResolver\Tests;

use ArgumentsResolver\InDepthArgumentResolver;

class InDepthArgumentsResolverTest extends ArgumentsResolverTest
{
    /**
     * @dataProvider provideFunctionTypes
     */
    public function testResolvingVariousOrdered($functionType)
    {
        $parameters = ['foo', new \stdClass(), ['baz'], 'qux'];

        $this->assertArguments($parameters, $parameters, $functionType, 'various');
    }

    /**
     * @dataProvider provideFunctionTypes
     */
    public function testResolvingVariousUnordered($functionType)
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
    public function testResolvingVariousOptional($functionType)
    {
        $parameters = ['foo', new \stdClass()];
        $arguments = array_merge($parameters, [[], null]);

        $this->assertArguments($arguments, $parameters, $functionType, 'various');
    }

    /**
     * @dataProvider provideFunctionTypes
     */
    public function testResolvingVariousByNameAndType($functionType)
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
    public function testResolvingObjectSameType($functionType)
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
    public function testResolvingObjectHierarchyType($functionType)
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
    public function testResolvingObjectHierarchyTypeReverse($functionType)
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
    public function testResolvingCallable($functionType)
    {
        $bar = function () {};

        $parameters = [$bar, 'foo', 'baz'];
        $arguments = ['foo', $bar, 'baz'];

        $this->assertArguments($arguments, $parameters, $functionType, 'callable');
    }

    /**
     * @dataProvider provideInvalidParameterTypes
     * @expectedException \ArgumentsResolver\UnresolvableArgumentException
     * @expectedExceptionMessage Unable to resolve argument
     */
    public function testResolvingThrowsExceptionOnInvalidType($functionType, $testCase, $parameters)
    {
        $this->resolveArguments($parameters, $functionType, $testCase);
    }

    public function provideInvalidParameterTypes()
    {
        $data = [];

        foreach (FunctionUtils::getTypes() as $type) {
            $data[] = [$type, 'array', [null, null, null]];
            $data[] = [$type, 'callable', [null, null, null]];
            $data[] = [$type, 'object_same', [null, null, null, null]];
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    protected function resolveArguments(array $parameters, $functionType, $testCase)
    {
        $reflection = FunctionUtils::createReflection($functionType, $testCase);
        $resolver = new InDepthArgumentResolver($reflection);

        return $resolver->resolveArguments($parameters);
    }
}
