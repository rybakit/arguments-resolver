<?php

namespace ArgumentsResolver\Tests;

abstract class ArgumentsResolverTest extends \PHPUnit_Framework_TestCase
{
    public function testResolvingVariousByName()
    {
        $bar = new \stdClass();
        $baz = ['baz'];

        $parameters = ['qux' => 'qux', 'baz' => $baz, 'bar' => $bar, 'foo' => 'foo'];
        $arguments = ['foo', $bar, $baz, 'qux'];

        $this->assertArguments($arguments, $parameters, 'various');
    }

    public function testResolvingOptional()
    {
        $parameters = ['mixed1' => 'foo', 'mixed2' => 'bar'];
        $arguments = ['foo', 'bar', 1, 2];

        $this->assertArguments($arguments, $parameters, 'optional');
    }

    public function testResolvingEmpty()
    {
        $parameters = ['foo'];
        $arguments = [];

        $this->assertArguments($arguments, $parameters, 'empty');
    }

    /**
     * @expectedException \ArgumentsResolver\UnresolvableArgumentException
     * @expectedExceptionMessage Unable to resolve argument
     */
    public function testResolvingThrowsExceptionOnEmptyParameters()
    {
        $this->resolveArguments([], 'various');
    }

    public function assertArguments(array $expected, array $actual, $testCase)
    {
        $this->assertSame($expected, $this->resolveArguments($actual, $testCase));
    }

    /**
     * @param string $testCase
     *
     * @return \ReflectionFunction
     */
    protected function createFunctionReflection($testCase)
    {
        return new \ReflectionFunction(__NAMESPACE__.'\Fixtures\function_'.$testCase);
    }

    /**
     * {@inheritdoc}
     */
    protected function resolveArguments(array $parameters, $testCase)
    {
        $reflection = $this->createFunctionReflection($testCase);
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
