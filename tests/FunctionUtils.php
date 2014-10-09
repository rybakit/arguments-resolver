<?php

namespace ArgumentsResolver\Tests;

class FunctionUtils
{
    const TYPE_METHOD           = 'method';
    const TYPE_STATIC_METHOD    = 'static_method';
    const TYPE_INVOKED_METHOD   = 'invoked_method';
    const TYPE_CLOSURE          = 'closure';
    const TYPE_FUNCTION         = 'function';

    /**
     * Returns an array of available function types.
     *
     * @return array
     */
    public static function getTypes()
    {
        return [
            self::TYPE_METHOD,
            self::TYPE_STATIC_METHOD,
            self::TYPE_INVOKED_METHOD,
            self::TYPE_CLOSURE,
            self::TYPE_FUNCTION,
        ];
    }

    /**
     * Creates a reflection object based on a function type and a test case.
     *
     * @param string $functionType
     * @param string $testCase
     *
     * @return \ReflectionFunction|\ReflectionMethod
     *
     * @throws \InvalidArgumentException
     */
    public static function createReflection($functionType, $testCase)
    {
        $namespace = __NAMESPACE__.'\Fixtures';

        switch ($functionType) {
            case self::TYPE_METHOD:
            case self::TYPE_STATIC_METHOD:
                return new \ReflectionMethod($namespace.'\TestClass', 'staticMethod'.self::camelize($testCase));

            case self::TYPE_INVOKED_METHOD:
                return new \ReflectionMethod($namespace.'\Invoke'.self::camelize($testCase).'Class', '__invoke');

            case self::TYPE_CLOSURE:
            case self::TYPE_FUNCTION:
                return new \ReflectionFunction($namespace.'\function_'.$testCase);
        }

        throw new \InvalidArgumentException(sprintf('Unsupported function type "%s".', $functionType));
    }

    /**
     * Converts a string to UpperCamelCase.
     *
     * @param string $string
     *
     * @return string
     */
    private static function camelize($string)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
    }
}
