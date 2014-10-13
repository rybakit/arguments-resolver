<?php

namespace ArgumentsResolver\Tests;

use ArgumentsResolver\Tests\Fixtures\TestClass;
use ArgumentsResolver\ReflectionFactory;

class ReflectionFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideFunctions
     */
    public function testCreatingReflection($function)
    {
        $reflection = ReflectionFactory::create($function);

        $this->assertInstanceOf('ReflectionFunctionAbstract', $reflection);
    }

    public function provideFunctions()
    {
        return [
            ['function'             => __NAMESPACE__.'\Fixtures\function_empty'],
            ['method'               => [__NAMESPACE__.'\Fixtures\TestClass', 'foo']],
            ['method_magic'         => [__NAMESPACE__.'\Fixtures\TestClass', '__construct']],
            ['static_method'        => [__NAMESPACE__.'\Fixtures\TestClass', 'bar']],
            ['static_method_string' => __NAMESPACE__.'\Fixtures\TestClass::bar'],
            ['invoker'              => new TestClass()],
            ['closure'              => function () {}],
        ];
    }
}
