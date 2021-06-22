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

class InDepthArgumentsResolver extends ArgumentsResolver
{
    /**
     * @var \ReflectionParameter[]
     */
    private $sortedParameters;

    /**
     * {@inheritdoc}
     */
    protected function getParameters() : array
    {
        if (null === $this->sortedParameters) {
            $this->sortedParameters = $this->reflection->getParameters();
            \uasort($this->sortedParameters, [__CLASS__, 'compareParameters']);
        }

        return $this->sortedParameters;
    }

    /**
     * {@inheritdoc}
     */
    protected function match(\ReflectionParameter $parameter, array $parameters) : ?array
    {
        $found = null;

        foreach ($parameters as $key => $value) {
            if (!self::matchType($parameter, $value)) {
                continue;
            }

            if ($key === $parameter->name) {
                return [$key, $value];
            }

            if (!$found) {
                $found = [$key, $value];
            }
        }

        return $found;
    }

    /**
     * Checks if the value matches the parameter type.
     *
     * @param mixed $value
     */
    private static function matchType(\ReflectionParameter $parameter, $value) : bool
    {
        if (!$type = $parameter->getType()) {
            return true;
        }

        $typeName = $type->getName();

        if ('array' === $typeName) {
            return \is_array($value);
        }

        if ('callable' === $typeName) {
            return \is_callable($value);
        }

        if (!$type->isBuiltin()) {
            if (!\is_object($value)) {
                return false;
            }

            $class = new \ReflectionClass($typeName);

            return $class && $class->isInstance($value);
        }

        switch ($typeName) {
            case 'bool': return \is_bool($value);
            case 'float': return \is_float($value);
            case 'int': return \is_int($value);
            case 'string': return \is_string($value);
            case 'iterable': return \is_iterable($value);
        }

        return true;
    }

    /**
     * Compares reflection parameters by type and position.
     */
    private static function compareParameters(\ReflectionParameter $a, \ReflectionParameter $b) : int
    {
        $aType = $a->getType();
        $bType = $b->getType();

        if (0 !== $result = self::compareParameterClasses($aType, $bType)) {
            return $result;
        }

        $aTypeName = $aType ? $aType->getName() : null;
        $bTypeName = $bType ? $bType->getName() : null;

        if (('array' === $aTypeName) ^ ('array' === $bTypeName)) {
            return ('array' === $bTypeName) << 1 - 1;
        }

        if (('callable' === $aTypeName) ^ ('callable' === $bTypeName)) {
            return ('callable' === $bTypeName) << 1 - 1;
        }

        return $a->getPosition() - $b->getPosition();
    }

    /**
     * Compares reflection parameters by class hierarchy.
     */
    private static function compareParameterClasses(?\ReflectionType $a, ?\ReflectionType $b) : int
    {
        $a = $a && !$a->isBuiltin()
            ? new \ReflectionClass($a->getName())
            : null;

        $b = $b && !$b->isBuiltin()
            ? new \ReflectionClass($b->getName())
            : null;

        if ($a && $b) {
            return $a->isSubclassOf($b->name) ? -1 : (int) $b->isSubclassOf($a->name);
        }

        return (int) !$a - (int) !$b;
    }
}
