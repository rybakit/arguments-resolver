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
use ArgumentsResolver\UnresolvableArgumentException;
use PHPUnit\Framework\TestCase;

abstract class ArgumentsResolverTest extends TestCase
{
    public function testResolvingByName() : void
    {
        $function = function ($foo, $bar, $baz) {};
        $result = ['foo', 'bar', 'baz'];
        $input = ['baz' => 'baz', 'bar' => 'bar', 'foo' => 'foo'];

        $this->assertArguments($function, $result, $input);
    }

    public function testResolvingOptional() : void
    {
        $function = function ($foo = 'foo', $bar = 'bar') {};
        $result = ['foo', 'bar'];
        $input = [];

        $this->assertArguments($function, $result, $input);
    }

    public function testResolvingEmpty() : void
    {
        $function = function () {};
        $result = [];
        $input = ['foo' => 'foo'];

        $this->assertArguments($function, $result, $input);
    }

    public function testResolvingThrowsExceptionOnEmptyParameters() : void
    {
        $this->expectException(UnresolvableArgumentException::class);
        $this->expectExceptionMessage('Unable to resolve argument');

        $this->resolveArguments(function ($foo) {}, []);
    }

    public function assertArguments(\Closure $function, array $result, array $input) : void
    {
        self::assertSame($result, $this->resolveArguments($function, $input));
    }

    protected function resolveArguments(\Closure $function, array $input) : array
    {
        $reflection = new \ReflectionFunction($function);
        $resolver = $this->createResolver($reflection);

        return $resolver->resolve($input);
    }

    abstract protected function createResolver(\ReflectionFunctionAbstract $function) : ArgumentsResolver;
}
