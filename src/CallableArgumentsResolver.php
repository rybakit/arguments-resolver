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

        $refParameters = $reflection->getParameters();
        usort($refParameters, 'sort_parameters');

        $arguments = [];
        foreach ($refParameters as $parameter) {
            $name = $parameter->getName();
            $pos = $parameter->getPosition();

            if ($typedParameters = filter_by_type($parameters, $parameter)) {
                if (array_key_exists($name, $typedParameters)) {
                    $value = $typedParameters[$name];
                    unset($parameters[$name]);
                } else {
                    $value = reset($typedParameters);
                    $key = key($typedParameters);
                    unset($parameters[$key]);
                }

                $arguments[$pos] = $value;
                continue;
            }

            if ($parameters && !has_type($parameter)) {
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

        return $arguments;
    }

    protected function getReflection()
    {
        if (!$this->reflection) {
            $this->reflection = create_reflection($this->callable);
        }

        return $this->reflection;
    }
}
