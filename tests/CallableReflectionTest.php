<?php

namespace CallableArgumentsResolver\Tests;

use CallableArgumentsResolver\CallableReflection;

class CallableReflectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \ReflectionFunctionAbstract
     */
    protected $reflection;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->reflection = new \ReflectionFunction(__NAMESPACE__.'\function_with_array');
    }

    public function testGettingReflection()
    {
        $callable = new CallableReflection($this->reflection);
        $this->assertSame($this->reflection, $callable->getReflection());
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
}
