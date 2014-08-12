<?php

namespace CallableArgumentsResolver\Tests;

use CallableArgumentsResolver\CallableArgumentsResolver;

class CallableArgumentsResolverTest extends \PHPUnit_Framework_TestCase
{
    use TestResolvingTrait;

    /**
     * @dataProvider provideCallableTypes
     */
    public function testGettingCallable($callableType)
    {
        $callable = create_callable($callableType, 'empty');
        $resolver = new CallableArgumentsResolver($callable);

        $this->assertSame($callable, $resolver->getCallable());
    }

    protected function resolveArguments(array $arguments, $type, $mode)
    {
        $callable = create_callable($type, $mode);
        $resolver = new CallableArgumentsResolver($callable);

        return $resolver->resolveArguments($arguments);
    }
}
