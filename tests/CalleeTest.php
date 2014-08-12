<?php

namespace CallableArgumentsResolver\Tests;

use CallableArgumentsResolver\Callee;

class CalleeTest extends \PHPUnit_Framework_TestCase
{
    use TestResolvingTrait;

    public function testGettingReflection()
    {
        $reflection = create_callable_reflection('function', 'array');
        $callee = new Callee($reflection);

        $this->assertSame($reflection, $callee->getReflection());
    }

    public function testGettingNameForFunction()
    {
        $functionName = __NAMESPACE__.'\function_array';
        $reflection = new \ReflectionFunction($functionName);

        $callee = new Callee($reflection);

        $this->assertEquals($functionName, $callee->getName());
    }

    public function testGettingNameForMethod()
    {
        $className = __NAMESPACE__.'\TestClass';
        $methodName = 'methodArray';

        $reflection = new \ReflectionMethod($className, $methodName);
        $callableName = sprintf('%s::%s()', $className, $methodName);

        $callee = new Callee($reflection);
        $this->assertEquals($callableName, $callee->getName());
    }

    protected function resolveArguments(array $arguments, $type, $mode)
    {
        $reflection = create_callable_reflection($type, $mode);
        $callee = new Callee($reflection);

        return $callee->resolveArguments($arguments);
    }
}
