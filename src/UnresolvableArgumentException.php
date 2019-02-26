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

class UnresolvableArgumentException extends \InvalidArgumentException
{
    public static function fromParameter(\ReflectionParameter $parameter) : self
    {
        return new self(\sprintf(
            'Unable to resolve argument $%s (#%d) of %s.',
            $parameter->name,
            $parameter->getPosition(),
            self::getFunctionName($parameter->getDeclaringFunction())
        ));
    }

    /**
     * @param \ReflectionFunctionAbstract $reflection
     *
     * @return string
     */
    private static function getFunctionName(\ReflectionFunctionAbstract $reflection) : string
    {
        $name = $reflection->name.'()';

        if ($reflection instanceof \ReflectionMethod) {
            $name = $reflection->getDeclaringClass()->name.'::'.$name;
        }

        return $name;
    }
}
