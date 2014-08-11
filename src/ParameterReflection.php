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
        return $this->hasTypehint()
            ? $this->doFindKeyByTypehint($parameters)
            : $this->doFindKey($parameters);
    }

    /**
     * Checks if the parameter has a typehint.
     *
     * @return bool
     */
    public function hasTypehint()
    {
        return $this->reflection->getClass()
            || $this->reflection->isArray()
            || $this->reflection->isCallable();
    }

    /**
     * Checks if the value match the parameter typehint.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function matchTypehint($value)
    {
        $class = $this->reflection->getClass();

        if ($class && is_object($value)) {
            return $class->isInstance($value);
        }

        if ($this->reflection->isArray() && is_array($value)) {
            return true;
        }

        if ($this->reflection->isCallable() && is_callable($value)) {
            return true;
        }

        return false;
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
     * @param array $parameters
     *
     * @return mixed
     */
    protected function doFindKey(array $parameters)
    {
        if (!$parameters) {
            return;
        }

        if (array_key_exists($this->reflection->name, $parameters)) {
            return $this->reflection->name;
        }

        reset($parameters);

        return key($parameters);
    }

    /**
     * @param array $parameters
     *
     * @return mixed
     */
    protected function doFindKeyByTypehint(array $parameters)
    {
        $found = null;

        foreach ($parameters as $key => $value) {
            if (!$this->matchTypehint($value)) {
                continue;
            }

            if ($key === $this->reflection->name) {
                return $key;
            }

            if (!$found) {
                $found = $key;
            }
        }

        return $found;
    }
}
