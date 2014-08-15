<?php

namespace CallableArgumentsResolver\Tests;

trait TestResolvingTrait
{
    /**
     * @dataProvider provideCallableTypes
     */
    public function testResolvingVariousOrdered($callableType)
    {
        $parameters = ['foo', new \stdClass(), ['baz'], 'qux'];

        $this->assertArguments($parameters, $parameters, $callableType, 'various');
    }

    /**
     * @dataProvider provideCallableTypes
     */
    public function testResolvingVariousUnordered($callableType)
    {
        $bar = new \stdClass();
        $baz = ['baz'];

        $parameters = ['foo', 'qux', $baz, $bar];
        $arguments = ['foo', $bar, $baz, 'qux'];

        $this->assertArguments($arguments, $parameters, $callableType, 'various');
    }

    /**
     * @dataProvider provideCallableTypes
     */
    public function testResolvingVariousOptional($callableType)
    {
        $parameters = ['foo', new \stdClass()];
        $arguments = array_merge($parameters, [[], null]);

        $this->assertArguments($arguments, $parameters, $callableType, 'various');
    }

    /**
     * @dataProvider provideCallableTypes
     */
    public function testResolvingVariousByName($callableType)
    {
        $bar = new \stdClass();
        $baz = ['baz'];

        $parameters = ['qux' => 'qux', 'baz' => $baz, 'bar' => $bar, 'foo' => 'foo'];
        $arguments = ['foo', $bar, $baz, 'qux'];

        $this->assertArguments($arguments, $parameters, $callableType, 'various');
    }

    /**
     * @dataProvider provideCallableTypes
     */
    public function testResolvingVariousByNameAndType($callableType)
    {
        $foo = (object) ['name' => 'foo'];
        $bar = (object) ['name' => 'bar'];

        $parameters = ['bar' => $bar, $foo];
        $arguments = [$foo, $bar, [], null];

        $this->assertArguments($arguments, $parameters, $callableType, 'various');
    }

    /**
     * @dataProvider provideCallableTypes
     */
    public function testResolvingOptional($callableType)
    {
        $parameters = ['foo', 'bar'];
        $arguments = array_merge($parameters, [1, 2]);

        $this->assertArguments($arguments, $parameters, $callableType, 'optional');

    }

    /**
     * @dataProvider provideCallableTypes
     */
    public function testResolvingObjectSameType($callableType)
    {
        $bar = (object) ['name' => 'bar'];
        $qux = (object) ['name' => 'qux'];

        $parameters = [$bar, 'foo', $qux, 'baz'];
        $arguments = ['foo', $bar, 'baz', $qux];

        $this->assertArguments($arguments, $parameters, $callableType, 'object_same');
    }

    /**
     * @dataProvider provideCallableTypes
     */
    public function testResolvingObjectHierarchyType($callableType)
    {
        $bar = new \Exception();
        $qux = new \RuntimeException();

        $parameters = [$qux, 'foo', $bar, 'baz'];
        $arguments = ['foo', $bar, 'baz', $qux];

        $this->assertArguments($arguments, $parameters, $callableType, 'object_hierarchy');
    }

    /**
     * @dataProvider provideCallableTypes
     */
    public function testResolvingCallable($callableType)
    {
        $bar = function () {};

        $parameters = [$bar, 'foo', 'baz'];
        $arguments = ['foo', $bar, 'baz'];

        $this->assertArguments($arguments, $parameters, $callableType, 'callable');
    }

    /**
     * @dataProvider provideCallableTypes
     */
    public function testResolvingEmpty($callableType)
    {
        $parameters = ['foo'];

        $this->assertArguments([], $parameters, $callableType, 'empty');
    }

    /**
     * @dataProvider provideCallableDataWithInvalidTypes
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unable to resolve argument
     */
    public function testResolvingThrowsExceptionOnInvalidType($callableType, $functionName, $parameters)
    {
        $this->resolveArguments($parameters, $callableType, $functionName);
    }

    /**
     * @dataProvider provideCallableDataWithRequiredArguments
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unable to resolve argument
     */
    public function testResolvingThrowsExceptionOnEmptyParameters($callableType, $mode)
    {
        $this->resolveArguments([], $callableType, $mode);
    }

    public function provideCallableDataWithInvalidTypes()
    {
        $data = [];

        foreach ($this->provideCallableTypes() as $type) {
            $data[] = [$type[0], 'array', [null, null, null]];
            $data[] = [$type[0], 'callable', [null, null, null]];
            $data[] = [$type[0], 'object_same', [null, null, null, null]];
        }

        return $data;
    }

    public function provideCallableDataWithRequiredArguments()
    {
        $data = [];

        foreach ($this->provideCallableTypes() as $type) {
            $data[] = [$type[0], 'various'];
        }

        return $data;
    }

    public function provideCallableTypes()
    {
        return [
            ['method'],
            ['static_method'],
            ['invoked_method'],
            ['closure'],
            ['function'],
        ];
    }

    public function assertArguments(array $expected, array $actual, $type, $mode)
    {
        $this->assertSame($expected, $this->resolveArguments($actual, $type, $mode));
    }

    abstract protected function resolveArguments(array $arguments, $type, $mode);

    /**
     * @see PHPUnit_Framework_Assert::assertSame()
     */
    abstract public static function assertSame($expected, $actual, $message = '');
}
