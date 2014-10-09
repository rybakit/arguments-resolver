<?php

namespace ArgumentsResolver\Tests;

abstract class ArgumentsResolverTest extends \PHPUnit_Framework_TestCase
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

    public function provideFunctionTypes()
    {
        return array_map(function ($type) { return [$type]; }, FunctionUtils::getTypes());
    }

    public function assertArguments(array $expected, array $actual, $functionType, $testCase)
    {
        $this->assertSame($expected, $this->resolveArguments($actual, $functionType, $testCase));
    }

    /**
     * @param array  $parameters
     * @param string $functionType
     * @param string $testCase
     *
     * @return array
     */
    abstract protected function resolveArguments(array $parameters, $functionType, $testCase);
}
