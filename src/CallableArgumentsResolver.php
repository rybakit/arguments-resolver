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
     * @param array $params
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    public function resolve(array $params)
    {
        $reflection = $this->getReflection();

        if (count($params) < $reflection->getNumberOfRequiredParameters()) {
            throw new \InvalidArgumentException('Not enough parameters are provided.');
        }

        $refParams = $reflection->getParameters();
        usort($refParams, 'sort_parameters');

        $args = [];
        foreach ($refParams as $param) {
            $pos = $param->getPosition();
            $key = has_type($param) ? find_key_by_type($param, $params) : find_key($param, $params);

            if (null !== $key) {
                $args[$pos] = $params[$key];
                unset($params[$key]);
                continue;
            }

            if ($param->isDefaultValueAvailable()) {
                $args[$pos] = $param->getDefaultValue();
                continue;
            }

            throw new \InvalidArgumentException(sprintf('Unable to resolve argument %s.', $param->name ? '$'.$param->name : '#'.$pos));
        }

        return $args;
    }

    protected function getReflection()
    {
        if (!$this->reflection) {
            $this->reflection = create_reflection($this->callable);
        }

        return $this->reflection;
    }
}
