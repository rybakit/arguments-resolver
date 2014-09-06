<?php

namespace ArgumentsResolver\Adapter;

class InDepthAdapter implements Adapter
{
    /**
     * {@inheritdoc}
     */
    public function prepare(array $parameters)
    {
        uasort($parameters, [__CLASS__, 'compareParameters']);

        return $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(\ReflectionParameter $parameter, array $parameters)
    {
        $found = false;

        foreach ($parameters as $key => $value) {
            if (!$this->matchType($parameter, $value)) {
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
    protected function matchType(\ReflectionParameter $parameter, $value)
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
     * Compares parameters by type and position.
     *
     * @param \ReflectionParameter $a
     * @param \ReflectionParameter $b
     *
     * @return int
     */
    protected static function compareParameters(\ReflectionParameter $a, \ReflectionParameter $b)
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
     * Compares parameters by class.
     *
     * @param \ReflectionParameter $a
     * @param \ReflectionParameter $b
     *
     * @return int
     */
    protected static function compareParameterClasses(\ReflectionParameter $a, \ReflectionParameter $b)
    {
        $a = $a->getClass();
        $b = $b->getClass();

        if ($a && $b) {
            return $a->isSubclassOf($b->name) ? -1 : (int) $b->isSubclassOf($a->name);
        }

        return !$a - !$b;
    }
}
