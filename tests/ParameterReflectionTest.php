<?php

namespace CallableArgumentsResolver\Tests;

use CallableArgumentsResolver\ParameterReflection;

class ParameterReflectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \ReflectionParameter
     */
    protected $reflection;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->reflection = new \ReflectionParameter(__NAMESPACE__.'\function_with_array', 1);
    }

    public function testGettingReflection()
    {
        $parameter = new ParameterReflection($this->reflection);
        $this->assertSame($this->reflection, $parameter->getReflection());
    }

    public function testGettingPosition()
    {
        $parameter = new ParameterReflection($this->reflection);
        $this->assertEquals(1, $parameter->getPosition());
    }

    public function testCheckingDefaultValueAvailability()
    {
        $reflection = new \ReflectionParameter(__NAMESPACE__.'\function_with_optional', 2);
        $parameter = new ParameterReflection($reflection);
        $this->assertTrue($parameter->isDefaultValueAvailable());
    }

    public function testGettingDefaultValue()
    {
        $reflection = new \ReflectionParameter(__NAMESPACE__.'\function_with_optional', 2);
        $parameter = new ParameterReflection($reflection);
        $this->assertEquals(1, $parameter->getDefaultValue());
    }

    public function testGettingPrettyName()
    {
        $parameter = new ParameterReflection($this->reflection);
        $this->assertEquals('$array (#1)', $parameter->getPrettyName());
    }
}
