<?php

namespace ArgumentsResolver\Tests;

use ArgumentsResolver\UnresolvableArgumentException;

class UnresolvableArgumentExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideReflectionData
     */
    public function testGettingErrorMessage($expectedName, \ReflectionFunctionAbstract $function)
    {
        $parameters = $function->getParameters();
        $exception = new UnresolvableArgumentException($parameters[0]);

        $this->assertRegExp(
            sprintf('/^Unable to resolve argument \$\w+ \(#0\) of %s\(\)\.$/', preg_quote($expectedName, '/')),
            $exception->getMessage()
        );
    }

    public function provideReflectionData()
    {
        $functionName = __NAMESPACE__.'\Fixtures\function_various';
        $methodName = __NAMESPACE__.'\Fixtures\TestClass::foo';

        return [
            [$functionName, new \ReflectionFunction($functionName)],
            [$methodName, new \ReflectionMethod($methodName)],
        ];
    }
}
