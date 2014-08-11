<?php

namespace CallableArgumentsResolver\Tests;

use CallableArgumentsResolver\CallableReflection;

class CallableReflectionTest extends \PHPUnit_Framework_TestCase
{
    use TestResolvingTrait;

    public function testGettingReflection()
    {
        $reflection = create_reflection('function', 'with_array');
        $callable = new CallableReflection($reflection);

        $this->assertSame($reflection, $callable->getReflection());
    }

    public function testGettingPrettyNameForFunction()
    {
        $functionName = __NAMESPACE__.'\function_with_array';
        $reflection = new \ReflectionFunction($functionName);

        $callable = new CallableReflection($reflection);
        $this->assertEquals($functionName, $callable->getPrettyName());
    }

    public function testGettingPrettyNameForMethod()
    {
        $className = __NAMESPACE__.'\TestClass';
        $methodName = 'methodWithArray';

        $reflection = new \ReflectionMethod($className, $methodName);
        $prettyName = sprintf('%s::%s', $className, $methodName);

        $callable = new CallableReflection($reflection);
        $this->assertEquals($prettyName, $callable->getPrettyName());
    }

    protected function resolveArguments(array $arguments, $type, $mode)
    {
        $reflection = create_reflection($type, $mode);
        $reflection = new CallableReflection($reflection);

        return $reflection->resolveArguments($arguments);
    }
}
