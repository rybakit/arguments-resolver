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
use ArgumentsResolver\InDepthArgumentsResolver;
use ArgumentsResolver\UnresolvableArgumentException;

final class InDepthArgumentsResolverTest extends ArgumentsResolverTest
{
    protected function createResolver(\ReflectionFunctionAbstract $function) : ArgumentsResolver
    {
        return new InDepthArgumentsResolver($function);
    }

    public function testResolvingByType() : void
    {
        $callable = static function () {};
        $stdClass = new \stdClass();
        $array = ['baz'];
        $iterable = new \ArrayIterator([1, 2]);
        $int = 42;
        $float = M_PI;
        $string = 'foobar';
        $mixed = null;

        $function = static function (
            callable $callable,
            \stdClass $stdClass,
            array $array,
            iterable $iterable,
            int $int,
            float $float,
            string $string,
            $mixed = null
        ) {
        };

        $result = [$callable, $stdClass, $array, $iterable, $int, $float, $string, $mixed];
        $input = [$mixed, $array, $float, $stdClass, $string, $callable, $int, $iterable];

        $this->assertArguments($function, $result, $input);
    }

    public function testResolvingOptionalByType() : void
    {
        $function = static function (callable $foo = null, array $bar = [], bool $baz = false) {};
        $result = [null, [], false];
        $input = [];

        $this->assertArguments($function, $result, $input);
    }

    public function testResolvingSameTypeByName() : void
    {
        $foo = (object) ['name' => 'foo'];
        $bar = (object) ['name' => 'bar'];

        $function = static function (\stdClass $foo, \stdClass $bar) {};
        $result = [$foo, $bar];
        $input = ['bar' => $bar, 'foo' => $foo];

        $this->assertArguments($function, $result, $input);
    }

    public function testResolvingByObjectHierarchy() : void
    {
        $foo = new \Exception();
        $bar = new \RuntimeException();

        $function = static function (\Exception $foo, \RuntimeException $bar) {};
        $result = [$foo, $bar];
        $input = [$bar, $foo];

        $this->assertArguments($function, $result, $input);
    }

    public function testResolvingByObjectHierarchyReversed() : void
    {
        $foo = new \RuntimeException();
        $bar = new \Exception();

        $function = static function (\RuntimeException $foo, \Exception $bar) {};
        $result = [$foo, $bar];
        $input = [$bar, $foo];

        $this->assertArguments($function, $result, $input);
    }

    /**
     * @dataProvider provideInvalidParameterTypes
     */
    public function testResolvingThrowsExceptionOnInvalidType(\Closure $function, array $input) : void
    {
        $this->expectException(UnresolvableArgumentException::class);
        $this->expectExceptionMessage('Unable to resolve argument');

        $this->resolveArguments($function, $input);
    }

    public function provideInvalidParameterTypes() : iterable
    {
        return [
            [static function (array $foo) {}, [42]],
            [static function (callable $foo) {}, [42]],
            [static function (\stdClass $foo) {}, [42]],
        ];
    }
}
