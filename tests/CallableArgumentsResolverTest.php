<?php

namespace CallableArgumentsResolver\Tests;

use CallableArgumentsResolver\ArgumentMatcher\ArgumentMatcher;
use CallableArgumentsResolver\CallableArgumentsResolver;

class CallableArgumentsResolverTest extends \PHPUnit_Framework_TestCase
{
    use ResolvingTrait;

    /**
     * @dataProvider provideCallableData
     */
    public function testGettingCallable($callableType, ArgumentMatcher $matcher)
    {
        $callable = create_callable($callableType, 'empty');
        $resolver = new CallableArgumentsResolver($callable, $matcher);

        $this->assertSame($callable, $resolver->getCallable());
    }

    protected function resolveArguments(array $arguments, $type, $mode, ArgumentMatcher $matcher)
    {
        $callable = create_callable($type, $mode);
        $resolver = new CallableArgumentsResolver($callable, $matcher);

        return $resolver->resolveArguments($arguments);
    }
}
