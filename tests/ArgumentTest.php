<?php

namespace CallableArgumentsResolver\Tests;

use CallableArgumentsResolver\Argument;

class ArgumentTest extends \PHPUnit_Framework_TestCase
{
    public function testGettingReflection()
    {
        $reflection = new \ReflectionParameter(__NAMESPACE__.'\function_array', 1);
        $argument = new Argument($reflection);

        $this->assertSame($reflection, $argument->getReflection());
    }

    public function testCheckingDefaultValueAvailability()
    {
        $reflection = new \ReflectionParameter(__NAMESPACE__.'\function_optional', 2);
        $argument = new Argument($reflection);

        $this->assertTrue($argument->hasDefaultValue());
    }

    public function testGettingDefaultValue()
    {
        $reflection = new \ReflectionParameter(__NAMESPACE__.'\function_optional', 2);
        $argument = new Argument($reflection);

        $this->assertEquals(1, $argument->getDefaultValue());
    }

    public function testGettingName()
    {
        $reflection = new \ReflectionParameter(__NAMESPACE__.'\function_various', 0);
        $argument = new Argument($reflection);

        $this->assertEquals('$foo (#0)', $argument->getName());
    }
}
