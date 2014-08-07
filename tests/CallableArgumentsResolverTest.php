<?php

/**
 * @see https://bugs.php.net/bug.php?id=50798
 * @see https://bugs.php.net/bug.php?id=67454
 */
class CallableArgumentsResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideCallableTypes
     */
    public function testResolvingAllOrdered($callableType)
    {
        $callable = create_callable($callableType, 'with_arguments');
        $resolver = new CallableArgumentsResolver($callable);

        $parameters = ['foo', new \stdClass(), ['baz'], 'qux'];

        $this->assertEquals($parameters, $resolver->getArguments($parameters));
    }

    /**
     * @dataProvider provideCallableTypes
     */
    public function testResolvingAllUnordered($callableType)
    {
        $callable = create_callable($callableType, 'with_arguments');
        $resolver = new CallableArgumentsResolver($callable);

        $bar = new \stdClass();
        $baz = ['baz'];

        $parameters = ['foo', 'qux', $baz, $bar];
        $arguments = ['foo', $bar, $baz, 'qux'];

        $this->assertEquals($arguments, $resolver->getArguments($parameters));
    }

    /**
     * @dataProvider provideCallableTypes
     */
    public function testResolvingOptional($callableType)
    {
        $callable = create_callable($callableType, 'with_arguments');
        $resolver = new CallableArgumentsResolver($callable);

        $parameters = ['foo', new \stdClass()];
        $arguments = array_merge($parameters, [[], null]);

        $this->assertEquals($arguments, $resolver->getArguments($parameters));
    }

    /**
     * @dataProvider provideCallableTypes
     */
    public function testResolvingByName($callableType)
    {
        $callable = create_callable($callableType, 'with_arguments');
        $resolver = new CallableArgumentsResolver($callable);

        $bar = new \stdClass();
        $baz = ['baz'];

        $parameters = ['qux' => 'qux', 'baz' => $baz, 'bar' => $bar, 'foo' => 'foo'];
        $arguments = ['foo', $bar, $baz, 'qux'];

        $this->assertEquals($arguments, $resolver->getArguments($parameters));
    }

    /**
     * @dataProvider provideCallableTypes
     */
    public function testResolvingByNameAndType($callableType)
    {
        $callable = create_callable($callableType, 'with_arguments');
        $resolver = new CallableArgumentsResolver($callable);

        $foo = (object) ['name' => 'foo'];
        $bar = (object) ['name' => 'bar'];

        $parameters = ['bar' => $bar, $foo];
        $arguments = [$foo, $bar, [], null];

        $this->assertEquals($arguments, $resolver->getArguments($parameters));
    }

    /**
     * @dataProvider provideCallableTypes
     */
    public function testResolvingWithoutArguments($callableType)
    {
        $callable = create_callable($callableType, 'without_arguments');
        $resolver = new CallableArgumentsResolver($callable);

        $parameters = ['foo'];

        $this->assertEquals([], $resolver->getArguments($parameters));
    }

    /**
     * @dataProvider provideCallableTypes
     */
    public function testResolvingArrayArgument($callableType)
    {
        $callable = create_callable($callableType, 'with_array_argument');
        $resolver = new CallableArgumentsResolver($callable);

        $parameters = [[1, 2], 'foo'];

        $this->assertEquals([[1, 2]], $resolver->getArguments($parameters));
    }

    /**
     * @dataProvider provideCallableTypes
     */
    public function testResolvingCallableArgument($callableType)
    {
        $callable = create_callable($callableType, 'with_callable_argument');
        $resolver = new CallableArgumentsResolver($callable);

        $foo = function () {};
        $parameters = [$foo, 'bar'];

        $this->assertEquals([$foo], $resolver->getArguments($parameters));
    }

    /**
     * @dataProvider provideCallableTypes
     */
    public function testResolvingObjectArgument($callableType)
    {
        $callable = create_callable($callableType, 'with_object_argument');
        $resolver = new CallableArgumentsResolver($callable);

        $foo = new \stdClass();
        $parameters = [$foo, 'bar'];

        $this->assertEquals([$foo], $resolver->getArguments($parameters));
    }

    /**
     * @dataProvider provideCallableDataWithInvalidTypes
     * @expectedException InvalidArgumentException
     */
    public function testResolvingThrowsExceptionOnInvalidType(callable $callable, $parameters)
    {
        $resolver = new CallableArgumentsResolver($callable);
        $resolver->getArguments($parameters);
    }

    public function provideCallableDataWithInvalidTypes()
    {
        $data = [];

        foreach ($this->provideCallableTypes() as $type) {
            $data[] = [create_callable($type[0], 'with_array_argument'), [null]];
            $data[] = [create_callable($type[0], 'with_callable_argument'), [null]];
            $data[] = [create_callable($type[0], 'with_object_argument'), [null]];
        }

        return $data;
    }

    /**
     * @dataProvider provideCallableDataWithRequiredArguments
     * @expectedException InvalidArgumentException
     */
    public function testResolvingThrowsExceptionOnEmptyParameters(callable $callable)
    {
        $resolver = new CallableArgumentsResolver($callable);
        $resolver->getArguments([]);
    }

    public function provideCallableDataWithRequiredArguments()
    {
        $data = [];

        foreach ($this->provideCallableTypes() as $type) {
            $data[] = [create_callable($type[0], 'with_arguments')];
        }

        return $data;
    }

    public function provideCallableTypes()
    {
        return [
            ['method'],
            ['invoked_method'],
            ['function'],
        ];
    }
}
