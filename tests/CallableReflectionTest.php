<?php

namespace CallableArgumentsResolver\Tests;

use CallableArgumentsResolver\CallableReflection;

class CallableReflectionTest extends \PHPUnit_Framework_TestCase
{
    use TestResolvingTrait;

    public function testGettingReflection()
    {
        $reflection = create_reflection('function', 'array');
        $callable = new CallableReflection($reflection);

        $this->assertSame($reflection, $callable->getReflection());
    }

    public function testGettingNameForFunction()
    {
        $functionName = __NAMESPACE__.'\function_array';
        $reflection = new \ReflectionFunction($functionName);

        $callable = new CallableReflection($reflection);
        $this->assertEquals($functionName, $callable->getName());
    }

    public function testGettingNameForMethod()
    {
        $className = __NAMESPACE__.'\TestClass';
        $methodName = 'methodArray';

        $reflection = new \ReflectionMethod($className, $methodName);
        $callableName = sprintf('%s::%s()', $className, $methodName);

        $callable = new CallableReflection($reflection);
        $this->assertEquals($callableName, $callable->getName());
    }

    protected function resolveArguments(array $arguments, $type, $mode)
    {
        $reflection = create_reflection($type, $mode);
        $reflection = new CallableReflection($reflection);

        return $reflection->resolveArguments($arguments);
    }
}
