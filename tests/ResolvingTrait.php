<?php

namespace ArgumentsResolver\Tests;

use ArgumentsResolver\Adapter\Adapter;
use ArgumentsResolver\Adapter\InDepthAdapter;
use ArgumentsResolver\Adapter\KeyAdapter;

trait ResolvingTrait
{
    use InDepthResolvingTrait;

    protected static function getAdapters()
    {
        return [
            'in_depth'  => new InDepthAdapter(),
            'key'       => new KeyAdapter(),
        ];
    }

    /**
     * @dataProvider provideCallableData
     */
    public function testResolvingVariousByName($callableType, Adapter $adapter)
    {
        $bar = new \stdClass();
        $baz = ['baz'];

        $parameters = ['qux' => 'qux', 'baz' => $baz, 'bar' => $bar, 'foo' => 'foo'];
        $arguments = ['foo', $bar, $baz, 'qux'];

        $this->assertArguments($arguments, $parameters, $callableType, 'various', $adapter);
    }

    /**
     * @dataProvider provideCallableData
     */
    public function testResolvingOptional($callableType, Adapter $adapter)
    {
        $parameters = ['mixed1' => 'foo', 'mixed2' => 'bar'];
        $arguments = ['foo', 'bar', 1, 2];

        $this->assertArguments($arguments, $parameters, $callableType, 'optional', $adapter);
    }

    /**
     * @dataProvider provideCallableData
     */
    public function testResolvingEmpty($callableType, Adapter $adapter)
    {
        $parameters = ['foo'];

        $this->assertArguments([], $parameters, $callableType, 'empty', $adapter);
    }

    /**
     * @dataProvider provideCallableData
     * @expectedException \ArgumentsResolver\UnresolvableArgumentException
     * @expectedExceptionMessage Unable to resolve argument
     */
    public function testResolvingThrowsExceptionOnEmptyParameters($callableType, Adapter $adapter)
    {
        $this->resolveArguments([], $callableType, 'various', $adapter);
    }

    public function provideCallableData($testMethodName)
    {
        $adapters = static::getAdapters();

        if (preg_match('/test(.+?)Resolving/', $testMethodName, $matches)) {
            $adapterName = strtolower(preg_replace('/(?<=[a-z])([A-Z])/', '_$1', $matches[1]));
            $adapters = [$adapterName => $adapters[$adapterName]];
        }

        $data = [];
        foreach (CallableTypes::getAll() as $type) {
            foreach ($adapters as $adapter) {
                $data[] = [$type, $adapter];
            }
        }

        return $data;
    }

    public function assertArguments(array $expected, array $actual, $type, $mode, Adapter $adapter)
    {
        $this->assertSame($expected, $this->resolveArguments($actual, $type, $mode, $adapter));
    }

    abstract protected function resolveArguments(array $arguments, $type, $mode, Adapter $adapter);

    /**
     * @see PHPUnit_Framework_Assert::assertSame()
     */
    abstract public function assertSame($expected, $actual, $message = '');
}
