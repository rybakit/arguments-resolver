<?php

namespace ArgumentsResolver;

class InDepthArgumentsResolver extends ArgumentsResolver
{
    /**
     * @var \ReflectionParameter[]
     */
    private $parameters;

    /**
     * {@inheritdoc}
     */
    protected function getParameters()
    {
        if (null === $this->parameters) {
            $this->parameters = $this->reflection->getParameters();
            uasort($this->parameters, [__CLASS__, 'compareParameters']);
        }

        return $this->parameters;
    }

    /**
     * {@inheritdoc}
     */
    protected function match(\ReflectionParameter $parameter, array $parameters)
    {
        $found = false;

        foreach ($parameters as $key => $value) {
            if (!self::matchType($parameter, $value)) {
                continue;
            }

            if ($key === $parameter->name) {
                return [$key, $value];
            }

            if (false === $found) {
                $found = [$key, $value];
            }
        }

        return $found;
    }

    /**
     * Checks if the value matches the parameter type.
     *
     * @param \ReflectionParameter $parameter
     * @param mixed                $value
     *
     * @return bool
     */
    protected static function matchType(\ReflectionParameter $parameter, $value)
    {
        if ($class = $parameter->getClass()) {
            return is_object($value) && $class->isInstance($value);
        }

        if ($parameter->isArray()) {
            return is_array($value);
        }

        if ($parameter->isCallable()) {
            return is_callable($value);
        }

        return true;
    }

    /**
     * Compares reflection parameters by type and position.
     *
     * @param \ReflectionParameter $a
     * @param \ReflectionParameter $b
     *
     * @return int
     */
    private static function compareParameters(\ReflectionParameter $a, \ReflectionParameter $b)
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
     *
     * @param \ReflectionParameter $a
     * @param \ReflectionParameter $b
     *
     * @return int
     */
    private static function compareParameterClasses(\ReflectionParameter $a, \ReflectionParameter $b)
    {
        $a = $a->getClass();
        $b = $b->getClass();

        if ($a && $b) {
            return $a->isSubclassOf($b->name) ? -1 : (int) $b->isSubclassOf($a->name);
        }

        return !$a - !$b;
    }
}
