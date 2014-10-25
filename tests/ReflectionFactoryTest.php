<?php

namespace ArgumentsResolver\Tests;

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
        $testClass = new TestClass();
        $testClassName = get_class($testClass);

        return [
            [$testClassName.'::foo',            [$testClassName, 'foo']],
            [$testClassName.'::foo',            [$testClass, 'foo']],
            [$testClassName.'::bar',            [$testClassName, 'bar']],
            [$testClassName.'::bar',            $testClassName.'::bar'],
            [$testClassName.'::__construct',    [$testClassName, '__construct']],
            [$testClassName.'::__construct',    [$testClass, '__construct']],
            [$testClassName.'::__invoke',       $testClass],
            ['Closure::__invoke',               function () {}],
            ['abs',                             'abs'],
        ];
    }

    /**
     * @param \ReflectionFunctionAbstract $reflection
     *
     * @return string
     */
    protected static function getFunctionName(\ReflectionFunctionAbstract $reflection)
    {
        if (!$reflection instanceof \ReflectionMethod) {
            return $reflection->name;
        }

        $class = $reflection->getDeclaringClass()->name;

        // see https://github.com/facebook/hhvm/issues/3874
        if (0 === strpos($class, 'Closure')) {
            $class = 'Closure';
        }

        return $class.'::'.$reflection->name;
    }
}
