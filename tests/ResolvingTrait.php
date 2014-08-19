<?php

namespace CallableArgumentsResolver\Tests;

use CallableArgumentsResolver\ArgumentMatcher\ArgumentMatcher;
use CallableArgumentsResolver\ArgumentMatcher\InDepthArgumentMatcher;
use CallableArgumentsResolver\ArgumentMatcher\KeyArgumentMatcher;

trait ResolvingTrait
{
    use InDepthResolvingTrait;

    protected static function getMatchers()
    {
        return [
            'in_depth'  => new InDepthArgumentMatcher(),
            'key'       => new KeyArgumentMatcher(),
        ];
    }

    /**
     * @dataProvider provideCallableData
     */
    public function testResolvingVariousByName($callableType, ArgumentMatcher $matcher)
    {
        $bar = new \stdClass();
        $baz = ['baz'];

        $parameters = ['qux' => 'qux', 'baz' => $baz, 'bar' => $bar, 'foo' => 'foo'];
        $arguments = ['foo', $bar, $baz, 'qux'];

        $this->assertArguments($arguments, $parameters, $callableType, 'various', $matcher);
    }

    /**
     * @dataProvider provideCallableData
     */
    public function testResolvingOptional($callableType, ArgumentMatcher $matcher)
    {
        $parameters = ['mixed1' => 'foo', 'mixed2' => 'bar'];
        $arguments = ['foo', 'bar', 1, 2];

        $this->assertArguments($arguments, $parameters, $callableType, 'optional', $matcher);
    }

    /**
     * @dataProvider provideCallableData
     */
    public function testResolvingEmpty($callableType, ArgumentMatcher $matcher)
    {
        $parameters = ['foo'];

        $this->assertArguments([], $parameters, $callableType, 'empty', $matcher);
    }

    /**
     * @dataProvider provideCallableData
     * @expectedException \CallableArgumentsResolver\UnresolvableArgumentException
     * @expectedExceptionMessage Unable to resolve argument
     */
    public function testResolvingThrowsExceptionOnEmptyParameters($callableType, ArgumentMatcher $matcher)
    {
        $this->resolveArguments([], $callableType, 'various', $matcher);
    }

    public function provideCallableData($testMethodName)
    {
        $matchers = static::getMatchers();

        if (preg_match('/test(.+?)Resolving/', $testMethodName, $matches)) {
            $matcherName = strtolower(preg_replace('/(?<=[a-z])([A-Z])/', '_$1', $matches[1]));
            $matchers = [$matcherName => $matchers[$matcherName]];
        }

        $data = [];
        foreach (CallableTypes::getAll() as $type) {
            foreach ($matchers as $matcher) {
                $data[] = [$type, $matcher];
            }
        }

        return $data;
    }

    public function assertArguments(array $expected, array $actual, $type, $mode, ArgumentMatcher $matcher)
    {
        $this->assertSame($expected, $this->resolveArguments($actual, $type, $mode, $matcher));
    }

    abstract protected function resolveArguments(array $arguments, $type, $mode, ArgumentMatcher $matcher);

    /**
     * @see PHPUnit_Framework_Assert::assertSame()
     */
    abstract public function assertSame($expected, $actual, $message = '');
}
