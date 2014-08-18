<?php

namespace CallableArgumentsResolver\ArgumentMatcher;

class InDepthArgumentMatcher implements ArgumentMatcher
{
    /**
     * {@inheritdoc}
     */
    public function filter(array $parameters)
    {
        uasort($parameters, [__CLASS__, 'compareParameters']);

        return $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function match(\ReflectionParameter $parameter, array $parameters)
    {
        $found = false;

        foreach ($parameters as $key => $value) {
            if (!$this->matchType($parameter, $value)) {
                continue;
            }

            if ($key === $parameter->name) {
                return $key;
            }

            if (false === $found) {
                $found = $key;
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
     * Compares parameters by type and optionality.
     *
     * @param \ReflectionParameter $a
     * @param \ReflectionParameter $b
     *
     * @return int
     */
    protected static function compareParameters(\ReflectionParameter $a, \ReflectionParameter $b)
    {
        if ($a->isOptional() ^ $b->isOptional()) {
            return $a->isOptional() << 1 - 1;
        }

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
        $aClass = $a->getClass();
        $bClass = $b->getClass();

        if (!$aClass && !$bClass) {
            return 0;
        }

        if (!$aClass || !$bClass) {
            return $aClass ? -1 : 1;
        }

        return $aClass->isSubclassOf($bClass->name) ? -1 : (int) $bClass->isSubclassOf($aClass->name);
    }
}
