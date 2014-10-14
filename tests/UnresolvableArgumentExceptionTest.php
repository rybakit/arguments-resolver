<?php

namespace ArgumentsResolver\Tests;

use ArgumentsResolver\UnresolvableArgumentException;

class UnresolvableArgumentExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testGettingErrorMessage()
    {
        $functionName = __NAMESPACE__.'\Fixtures\function_various';
        $function = new \ReflectionFunction($functionName);

        $parameter = reset($function->getParameters());
        $exception = new UnresolvableArgumentException($parameter);

        $this->assertRegExp(
            sprintf('/^Unable to resolve argument \$\w+ \(#0\) of %s\(\)\.$/', preg_quote($functionName, '/')),
            $exception->getMessage()
        );
    }
}
