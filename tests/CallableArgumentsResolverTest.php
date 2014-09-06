<?php

namespace ArgumentsResolver\Tests;

use ArgumentsResolver\Adapter\Adapter;
use ArgumentsResolver\CallableArgumentsResolver;

class CallableArgumentsResolverTest extends \PHPUnit_Framework_TestCase
{
    use ResolvingTrait;

    /**
     * @dataProvider provideCallableData
     */
    public function testGettingCallable($callableType, Adapter $adapter)
    {
        $callable = create_callable($callableType, 'empty');
        $resolver = new CallableArgumentsResolver($callable, $adapter);

        $this->assertSame($callable, $resolver->getCallable());
    }

    protected function resolveArguments(array $arguments, $type, $mode, Adapter $adapter)
    {
        $callable = create_callable($type, $mode);
        $resolver = new CallableArgumentsResolver($callable, $adapter);

        return $resolver->resolveArguments($arguments);
    }
}
