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
        if ($class = $parameter->getClass()) {
            return \is_object($value) && $class->isInstance($value);
        }

        if ($parameter->isArray()) {
            return \is_array($value);
        }

        if ($parameter->isCallable()) {
            return \is_callable($value);
        }

        if (!$type = $parameter->getType()) {
            return true;
        }

        switch ($type->getName()) {
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
        if (0 !== $result = self::compareParameterClasses($a, $b)) {
            return $result;
        }

        if ($a->isArray() ^ $b->isArray()) {
            return $b->isArray() << 1 - 1;
        }

        if ($a->isCallable() ^ $b->isCallable()) {
            return $b->isCallable() << 1 - 1;
        }

        return $a->getPosition() - $b->getPosition();
    }

    /**
     * Compares reflection parameters by class hierarchy.
     */
    private static function compareParameterClasses(\ReflectionParameter $a, \ReflectionParameter $b) : int
    {
        $a = $a->getClass();
        $b = $b->getClass();

        if ($a && $b) {
            return $a->isSubclassOf($b->name) ? -1 : (int) $b->isSubclassOf($a->name);
        }

        return (int) !$a - (int) !$b;
    }
}
