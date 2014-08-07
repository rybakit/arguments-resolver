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
            $pos = $parameter->getPosition();

            $found = has_type($parameter)
                ? find_by_type($parameter, $parameters)
                : find($parameter, $parameters);

            if ($found) {
                unset($parameters[$found[0]]);
                $arguments[$pos] = $found[1];
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
