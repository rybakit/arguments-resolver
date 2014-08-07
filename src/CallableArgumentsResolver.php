<?php

class CallableArgumentsResolver
{
    private $callable;
    private $reflection;

    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * @param array $parameters
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    public function resolve(array $parameters)
    {
        $reflection = $this->getReflection();

        if (count($parameters) < $reflection->getNumberOfRequiredParameters()) {
            throw new \InvalidArgumentException('Not enough parameters are provided.');
        }

        $reflParameters = $reflection->getParameters();
        usort($reflParameters, [$this, 'sortParameters']);

        $arguments = [];
        foreach ($reflParameters as $parameter) {
            $matched = [];
            foreach ($parameters as $key => $value) {
                if (static::matchArgumentType($parameter, $value)) {
                    $matched[$key] = $value;
                }
            }

            $name = $parameter->getName();
            $pos = $parameter->getPosition();

            if (!$matched) {
                if ($parameters && !$parameter->getClass() && !$parameter->isCallable() && !$parameter->isArray()) {
                    if (array_key_exists($name, $parameters)) {
                        $value = $parameters[$name];
                        unset($parameters[$name]);
                    } else {
                        $value = reset($parameters);
                        $key = key($parameters);
                        unset($parameters[$key]);
                    }

                    $arguments[$pos] = $value;
                    continue;
                }

                if ($parameter->isDefaultValueAvailable()) {
                    $arguments[$pos] = $parameter->getDefaultValue();
                    continue;
                }

                throw new \InvalidArgumentException(sprintf('Unable to resolve argument %s.', $parameter->name ? '$'.$parameter->name : '#'.$pos));
            }

            if (array_key_exists($name, $matched)) {
                $value = $matched[$name];
                unset($parameters[$name]);
            } else {
                $value = reset($matched);
                $key = key($matched);
                unset($parameters[$key]);
            }

            $arguments[$pos] = $value;
        }

        return $arguments;
    }

    public function sortParameters(ReflectionParameter $a, ReflectionParameter $b)
    {
        if ($a->isOptional() ^ $b->isOptional()) {
            return (($a->isOptional() > $b->isOptional()) << 1) - 1;
        }
        if ($a->getClass() && !$b->getClass()) {
            return -1;
        }
        if (!$a->getClass() && $b->getClass()) {
            return 1;
        }
        if ($a->isArray() ^ $b->isArray()) {
            return (($a->isArray() < $b->isArray()) << 1) - 1;
        }
        if ($a->isCallable() ^ $b->isCallable()) {
            return (($a->isCallable() < $b->isCallable()) << 1) - 1;
        }

        return $a->getPosition() - $b->getPosition();
    }

    protected function getReflection()
    {
        if (!$this->reflection) {
            $this->reflection = $this->createReflection();
        }

        return $this->reflection;
    }

    protected function createReflection()
    {
        if (is_array($this->callable)) {
            return new \ReflectionMethod($this->callable[0], $this->callable[1]);
        }

        if (is_object($this->callable) && !$this->callable instanceof \Closure) {
            return (new \ReflectionObject($this->callable))->getMethod('__invoke');
        }

        return new \ReflectionFunction($this->callable);
    }

    protected static function matchArgumentType(\ReflectionParameter $parameter, $value)
    {
        $refClass = $parameter->getClass();

        if ($refClass && is_object($value) && $refClass->isInstance($value)) {
            return true;
        }

        if ($parameter->isArray() && is_array($value)) {
            return true;
        }

        if ($parameter->isCallable() && is_callable($value)) {
            return true;
        }

        return false;
    }
}
