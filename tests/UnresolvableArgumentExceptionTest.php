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

use ArgumentsResolver\UnresolvableArgumentException;
use PHPUnit\Framework\TestCase;

final class UnresolvableArgumentExceptionTest extends TestCase
{
    /**
     * @dataProvider provideReflectionData
     */
    public function testGettingErrorMessage(string $expectedName, \ReflectionFunctionAbstract $function) : void
    {
        $parameters = $function->getParameters();
        $exception = UnresolvableArgumentException::fromParameter($parameters[0]);

        self::assertRegExp(
            sprintf('/^Unable to resolve argument \$\w+ \(#0\) of %s\(\)\.$/', preg_quote($expectedName, '/')),
            $exception->getMessage()
        );
    }

    public function provideReflectionData() : iterable
    {
        $functionName = 'abs';
        $methodName = __NAMESPACE__.'\TestClass::foo';

        return [
            [$functionName, new \ReflectionFunction($functionName)],
            [$methodName, new \ReflectionMethod($methodName)],
        ];
    }
}
