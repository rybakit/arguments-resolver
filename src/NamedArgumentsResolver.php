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

class NamedArgumentsResolver extends ArgumentsResolver
{
    /**
     * {@inheritdoc}
     */
    protected function match(\ReflectionParameter $parameter, array $parameters) : ?array
    {
        return \array_key_exists($parameter->name, $parameters)
            ? [$parameter->name, $parameters[$parameter->name]]
            : null;
    }
}
