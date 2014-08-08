<?php

namespace CallableArgumentsResolver\Tests;

use CallableArgumentsResolver\CallableArgumentsResolver;

/**
 * @see https://bugs.php.net/bug.php?id=50798
 * @see https://bugs.php.net/bug.php?id=67454
 */
class CallableArgumentsResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideCallableTypes
     */
    public function testResolvingVariousOrdered($callableType)
    {
        $callable = create_callable($callableType, 'with_various');
        $resolver = new CallableArgumentsResolver($callable);

        $parameters = ['foo', new \stdClass(), ['baz'], 'qux'];

        $this->assertSame($parameters, $resolver->resolve($parameters));
    }

    /**
     * @dataProvider provideCallableTypes
     */
    public function testResolvingVariousUnordered($callableType)
    {
        $callable = create_callable($callableType, 'with_various');
        $resolver = new CallableArgumentsResolver($callable);

        $bar = new \stdClass();
        $baz = ['baz'];

        $parameters = ['foo', 'qux', $baz, $bar];
        $arguments = ['foo', $bar, $baz, 'qux'];

        $this->assertSame($arguments, $resolver->resolve($parameters));
    }

    /**
     * @dataProvider provideCallableTypes
     */
    public function testResolvingVariousOptional($callableType)
    {
        $callable = create_callable($callableType, 'with_various');
        $resolver = new CallableArgumentsResolver($callable);

        $parameters = ['foo', new \stdClass()];
        $arguments = array_merge($parameters, [[], null]);

        $this->assertSame($arguments, $resolver->resolve($parameters));
    }

    /**
     * @dataProvider provideCallableTypes
     */
    public function testResolvingVariousByName($callableType)
    {
        $callable = create_callable($callableType, 'with_various');
        $resolver = new CallableArgumentsResolver($callable);

        $bar = new \stdClass();
        $baz = ['baz'];

        $parameters = ['qux' => 'qux', 'baz' => $baz, 'bar' => $bar, 'foo' => 'foo'];
        $arguments = ['foo', $bar, $baz, 'qux'];

        $this->assertSame($arguments, $resolver->resolve($parameters));
    }

    /**
     * @dataProvider provideCallableTypes
     */
    public function testResolvingVariousByNameAndType($callableType)
    {
        $callable = create_callable($callableType, 'with_various');
        $resolver = new CallableArgumentsResolver($callable);

        $foo = (object) ['name' => 'foo'];
        $bar = (object) ['name' => 'bar'];

        $parameters = ['bar' => $bar, $foo];
        $arguments = [$foo, $bar, [], null];

        $this->assertSame($arguments, $resolver->resolve($parameters));
    }

    /**
     * @dataProvider provideCallableTypes
     */
    public function testResolvingOptional($callableType)
    {
        $callable = create_callable($callableType, 'with_optional');
        $resolver = new CallableArgumentsResolver($callable);

        $parameters = ['foo', 'bar'];
        $arguments = array_merge($parameters, [1, 2]);

        $this->assertSame($arguments, $resolver->resolve($parameters));
    }

    /**
     * @dataProvider provideCallableTypes
     */
    public function testResolvingWithoutArguments($callableType)
    {
        $callable = create_callable($callableType, 'without_arguments');
        $resolver = new CallableArgumentsResolver($callable);

        $parameters = ['foo'];

        $this->assertSame([], $resolver->resolve($parameters));
    }

    /**
     * @dataProvider provideCallableDataWithInvalidTypes
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unable to resolve argument
     */
    public function testResolvingThrowsExceptionOnInvalidType(callable $callable, $parameters)
    {
        $resolver = new CallableArgumentsResolver($callable);
        $resolver->resolve($parameters);
    }

    /**
     * @dataProvider provideCallableDataWithRequiredArguments
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Not enough parameters are provided
     */
    public function testResolvingThrowsExceptionOnEmptyParameters(callable $callable)
    {
        $resolver = new CallableArgumentsResolver($callable);
        $resolver->resolve([]);
    }

    /**
     * @dataProvider provideCallableTypes
     */
    public function testRetrievingCallable($callableType)
    {
        $callable = create_callable($callableType, 'without_arguments');
        $resolver = new CallableArgumentsResolver($callable);

        $this->assertSame($callable, $resolver->getCallable());
    }

    public function provideCallableDataWithInvalidTypes()
    {
        $data = [];

        foreach ($this->provideCallableTypes() as $type) {
            $data[] = [create_callable($type[0], 'with_array'), [null, null, null]];
            $data[] = [create_callable($type[0], 'with_callable'), [null, null, null]];
            $data[] = [create_callable($type[0], 'with_object'), [null, null, null]];
        }

        return $data;
    }

    public function provideCallableDataWithRequiredArguments()
    {
        $data = [];

        foreach ($this->provideCallableTypes() as $type) {
            $data[] = [create_callable($type[0], 'with_various')];
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
}
