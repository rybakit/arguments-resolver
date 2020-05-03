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

/**
 * A compatibility layer for the legacy PHPUnit 7.
 */
trait PhpUnitCompat
{
    public static function assertMatchesRegularExpression(string $pattern, string $string, string $message = '') : void
    {
        if (\is_callable('parent::assertMatchesRegularExpression')) {
            parent::assertMatchesRegularExpression(...func_get_args());

            return;
        }

        parent::assertRegExp(...func_get_args());
    }
}
