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

namespace ArgumentsResolver;

abstract class ReflectionFactory
{
    /**
     * Creates a reflection for a given function.
     *
     * @param callable|string $function A callable or a string representing a class method (non-static, delimited by ::)
     *
     * @throws \ReflectionException
     *
     * @return \ReflectionFunction|\ReflectionMethod
     */
    public static function create($function) : \ReflectionFunctionAbstract
    {
        if (\is_string($function)) {
            return \strpos($function, '::') ? new \ReflectionMethod($function) : new \ReflectionFunction($function);
        }

        if (\is_array($function)) {
            return (new \ReflectionClass(\ReflectionMethod::class))->newInstanceArgs($function);
        }

        if (\method_exists($function, '__invoke')) {
            return new \ReflectionMethod($function, '__invoke');
        }

        return new \ReflectionFunction($function);
    }
}
