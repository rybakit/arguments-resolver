<?php

namespace ArgumentsResolver\Tests;

use ArgumentsResolver\InDepthArgumentsResolver;

class InDepthArgumentsResolverTest extends ArgumentsResolverTest
{
    /**
     * {@inheritdoc}
     */
    protected function createResolver(\ReflectionFunctionAbstract $function)
    {
        return new InDepthArgumentsResolver($function);
    }

    public function testResolvingByType()
    {
        $foo = function () {};
        $bar = new \stdClass();
        $baz = ['baz'];

        $function = function (callable $foo, \stdClass $bar, array $baz, $qux = null) {};
        $arguments = [$foo, $bar, $baz, 'qux'];
        $parameters = ['qux', $baz, $foo, $bar];

        $this->assertArguments($function, $arguments, $parameters);
    }

    public function testResolvingOptionalByType()
    {
        $function = function (callable $foo = null, array $bar = []) {};
        $arguments = [null, []];
        $parameters = [];

        $this->assertArguments($function, $arguments, $parameters);
    }

    public function testResolvingSameTypeByName()
    {
        $foo = (object) ['name' => 'foo'];
        $bar = (object) ['name' => 'bar'];

        $function = function (\stdClass $foo, \stdClass $bar) {};
        $arguments = [$foo, $bar];
        $parameters = ['bar' => $bar, 'foo' => $foo];

        $this->assertArguments($function, $arguments, $parameters);
    }

    public function testResolvingByObjectHierarchy()
    {
        $foo = new \Exception();
        $bar = new \RuntimeException();

        $function = function (\Exception $foo, \RuntimeException $bar) {};
        $arguments = [$foo, $bar];
        $parameters = [$bar, $foo];

        $this->assertArguments($function, $arguments, $parameters);
    }

    public function testResolvingByObjectHierarchyReversed()
    {
        $foo = new \RuntimeException();
        $bar = new \Exception();

        $function = function (\RuntimeException $foo, \Exception $bar) {};
        $arguments = [$foo, $bar];
        $parameters = [$bar, $foo];

        $this->assertArguments($function, $arguments, $parameters);
    }

    /**
     * @dataProvider provideInvalidParameterTypes
     * @expectedException \ArgumentsResolver\UnresolvableArgumentException
     * @expectedExceptionMessage Unable to resolve argument
     */
    public function testResolvingThrowsExceptionOnInvalidType($function, $parameters)
    {
        $this->resolveArguments($function, $parameters);
    }

    public function provideInvalidParameterTypes()
    {
        return [
            [function (array $foo) {}, [42]],
            [function (callable $foo) {}, [42]],
            [function (\stdClass $foo) {}, [42]],
        ];
    }
}
