<?php

namespace ArgumentsResolver\Tests;

abstract class ArgumentsResolverTest extends \PHPUnit_Framework_TestCase
{
    public function testResolvingByName()
    {
        $function = function ($foo, $bar, $baz) {};
        $arguments = ['foo', 'bar', 'baz'];
        $parameters = ['baz' => 'baz', 'bar' => 'bar', 'foo' => 'foo'];

        $this->assertArguments($function, $arguments, $parameters);
    }

    public function testResolvingOptional()
    {
        $function = function ($foo = 'foo', $bar = 'bar') {};
        $arguments = ['foo', 'bar'];
        $parameters = [];

        $this->assertArguments($function, $arguments, $parameters);
    }

    public function testResolvingEmpty()
    {
        $function = function () {};
        $arguments = [];
        $parameters = ['foo' => 'foo'];

        $this->assertArguments($function, $arguments, $parameters);
    }

    /**
     * @expectedException \ArgumentsResolver\UnresolvableArgumentException
     * @expectedExceptionMessage Unable to resolve argument
     */
    public function testResolvingThrowsExceptionOnEmptyParameters()
    {
        $this->resolveArguments(function ($foo) {}, []);
    }

    public function assertArguments(\Closure $function, array $expected, array $actual)
    {
        $this->assertSame($expected, $this->resolveArguments($function, $actual));
    }

    /**
     * @param \Closure $function
     * @param array    $parameters
     *
     * @return array
     */
    protected function resolveArguments(\Closure $function, array $parameters)
    {
        $reflection = new \ReflectionFunction($function);
        $resolver = $this->createResolver($reflection);

        return $resolver->resolve($parameters);
    }

    /**
     * @param \ReflectionFunctionAbstract $function
     *
     * @return \ArgumentsResolver\ArgumentsResolver
     */
    abstract protected function createResolver(\ReflectionFunctionAbstract $function);
}
