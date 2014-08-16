<?php

namespace CallableArgumentsResolver\Tests;

use CallableArgumentsResolver\ArgumentMatcher\InDepthArgumentMatcher;
use CallableArgumentsResolver\ArgumentsResolver;

class ArgumentsResolverTest extends \PHPUnit_Framework_TestCase
{
    use TestResolvingTrait;

    public function testGettingReflection()
    {
        $reflection = create_callable_reflection('function', 'array');
        $resolver = new ArgumentsResolver($reflection, new InDepthArgumentMatcher());

        $this->assertSame($reflection, $resolver->getReflection());
    }

    public function testGettingCallableNameForFunction()
    {
        $functionName = __NAMESPACE__.'\function_array';
        $calleeName = $functionName.'()';

        $reflection = new \ReflectionFunction($functionName);
        $resolver = new ArgumentsResolver($reflection, new InDepthArgumentMatcher());

        $this->assertEquals($calleeName, $resolver->getCallableName());
    }

    public function testGettingCallableNameForMethod()
    {
        $className = __NAMESPACE__.'\TestClass';
        $methodName = 'methodArray';
        $callableName = sprintf('%s::%s()', $className, $methodName);

        $reflection = new \ReflectionMethod($className, $methodName);
        $resolver = new ArgumentsResolver($reflection, new InDepthArgumentMatcher());

        $this->assertEquals($callableName, $resolver->getCallableName());
    }

    protected function resolveArguments(array $arguments, $type, $mode)
    {
        $reflection = create_callable_reflection($type, $mode);
        $callee = new ArgumentsResolver($reflection, new InDepthArgumentMatcher());

        return $callee->resolveArguments($arguments);
    }
}
