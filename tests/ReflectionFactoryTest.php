<?php

declare(strict_types=1);

/*
 * This file is part of the rybakit/arguments-resolver package.
 *
 * (c) Eugene Leonovich <gen.work@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ArgumentsResolver\Tests;

use ArgumentsResolver\ReflectionFactory;
use PHPUnit\Framework\TestCase;

final class ReflectionFactoryTest extends TestCase
{
    /**
     * @dataProvider provideFunctionData
     */
    public function testCreatingReflection(string $expectedName, $function)
    {
        $reflection = ReflectionFactory::create($function);

        self::assertSame($expectedName, self::getFunctionName($reflection));
    }

    public function provideFunctionData() : iterable
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

    private static function getFunctionName(\ReflectionFunctionAbstract $reflection) : string
    {
        if (!$reflection instanceof \ReflectionMethod) {
            return $reflection->name;
        }

        $class = $reflection->getDeclaringClass()->name;

        return $class.'::'.$reflection->name;
    }
}
