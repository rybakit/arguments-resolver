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

use ArgumentsResolver\ArgumentsResolver;
use ArgumentsResolver\NamedArgumentsResolver;

final class NamedArgumentsResolverTest extends ArgumentsResolverTest
{
    protected function createResolver(\ReflectionFunctionAbstract $function) : ArgumentsResolver
    {
        return new NamedArgumentsResolver($function);
    }
}
