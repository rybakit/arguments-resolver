<?php

namespace CallableArgumentsResolver\Tests;

use CallableArgumentsResolver\ParameterReflection;

class ParameterReflectionTest extends \PHPUnit_Framework_TestCase
{
    public function testGettingReflection()
    {
        $reflection = new \ReflectionParameter(__NAMESPACE__.'\function_with_array', 1);
        $parameter = new ParameterReflection($reflection);

        $this->assertSame($reflection, $parameter->getReflection());
    }

    public function testCheckingDefaultValueAvailability()
    {
        $reflection = new \ReflectionParameter(__NAMESPACE__.'\function_with_optional', 2);
        $parameter = new ParameterReflection($reflection);

        $this->assertTrue($parameter->hasDefaultValue());
    }

    public function testGettingDefaultValue()
    {
        $reflection = new \ReflectionParameter(__NAMESPACE__.'\function_with_optional', 2);
        $parameter = new ParameterReflection($reflection);

        $this->assertEquals(1, $parameter->getDefaultValue());
    }

    public function testGettingName()
    {
        $reflection = new \ReflectionParameter(__NAMESPACE__.'\function_with_various', 0);
        $parameter = new ParameterReflection($reflection);

        $this->assertEquals('$foo (#0)', $parameter->getName());
    }
}
