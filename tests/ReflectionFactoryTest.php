<?php

namespace ArgumentsResolver\Tests;

use ArgumentsResolver\Tests\Fixtures\TestClass;
use ArgumentsResolver\ReflectionFactory;

class ReflectionFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideFunctionData
     */
    public function testCreatingReflection($expectedName, $function)
    {
        $reflection = ReflectionFactory::create($function);

        $this->assertInstanceOf('ReflectionFunctionAbstract', $reflection);
        $this->assertSame($expectedName, self::getFunctionName($reflection));
    }

    public function provideFunctionData()
    {
        $nsPrefix = __NAMESPACE__.'\\Fixtures\\';

        return [
            [$nsPrefix.'function_empty',            $nsPrefix.'function_empty'],
            [$nsPrefix.'TestClass::foo',            [$nsPrefix.'TestClass', 'foo']],
            [$nsPrefix.'TestClass::__construct',    [$nsPrefix.'TestClass', '__construct']],
            [$nsPrefix.'TestClass::bar',            [$nsPrefix.'TestClass', 'bar']],
            [$nsPrefix.'TestClass::bar',            $nsPrefix.'TestClass::bar'],
            [$nsPrefix.'TestClass::__invoke',       new TestClass()],
            [$nsPrefix.'TestClass::foo',            [new TestClass(), 'foo']],
            [__NAMESPACE__.'\\{closure}',           function () {}],
        ];
    }

    /**
     * @param \ReflectionFunctionAbstract $reflection
     *
     * @return string
     */
    protected static function getFunctionName(\ReflectionFunctionAbstract $reflection)
    {
        $name = $reflection->name;

        if ($reflection instanceof \ReflectionMethod) {
            $name = $reflection->getDeclaringClass()->name.'::'.$name;
        }

        return $name;
    }
}
