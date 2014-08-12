<?php

namespace CallableArgumentsResolver;

class ParameterReflection
{
    /**
     * @var \ReflectionParameter
     */
    private $reflection;

    public function __construct(\ReflectionParameter $reflection)
    {
        $this->reflection = $reflection;
    }

    /**
     * Returns the parameter being wrapped.
     *
     * @return \ReflectionParameter
     */
    public function getReflection()
    {
        return $this->reflection;
    }

    /**
     * Returns the parameter position.
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->reflection->getPosition();
    }

    /**
     * Checks if a default value is available.
     *
     * @return bool
     */
    public function isDefaultValueAvailable()
    {
        return $this->reflection->isDefaultValueAvailable();
    }

    /**
     * Returns default parameter value.
     *
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->reflection->getDefaultValue();
    }

    /**
     * Returns a position of the first matched value or null otherwise.
     *
     * @param array $parameters
     *
     * @return mixed
     */
    public function findKey(array $parameters)
    {
        $found = null;

        foreach ($parameters as $key => $value) {
            if (!$this->matchType($value)) {
                continue;
            }

            if ($key === $this->reflection->name) {
                return $key;
            }

            if (null === $found) {
                $found = $key;
            }
        }

        return $found;
    }

    /**
     * Returns the parameter's pretty name.
     *
     * @return string
     */
    public function getPrettyName()
    {
        return sprintf('$%s (#%d)', $this->reflection->name, $this->reflection->getPosition());
    }

    /**
     * Checks if the value match the parameter type.
     *
     * @param mixed $value
     *
     * @return bool
     */
    protected function matchType($value)
    {
        if ($class = $this->reflection->getClass()) {
            return is_object($value) && $class->isInstance($value);
        }

        if ($this->reflection->isArray()) {
            return is_array($value);
        }

        if ($this->reflection->isCallable()) {
            return is_callable($value);
        }

        return true;
    }
}
