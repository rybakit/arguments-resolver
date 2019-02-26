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

abstract class ArgumentsResolver
{
    /**
     * @var \ReflectionFunctionAbstract
     */
    protected $reflection;

    public function __construct($function)
    {
        $this->reflection = $function instanceof \ReflectionFunctionAbstract
            ? $function
            : ReflectionFactory::create($function);
    }

    /**
     * Resolves function arguments.
     *
     * @param array $parameters
     *
     * @throws UnresolvableArgumentException
     * @throws \ReflectionException
     *
     * @return array
     */
    public function resolve(array $parameters) : array
    {
        if (!$number = $this->reflection->getNumberOfParameters()) {
            return [];
        }

        $arguments = \array_fill(0, $number, null);

        foreach ($this->getParameters() as $pos => $parameter) {
            $result = $this->match($parameter, $parameters);

            if ($result) {
                $arguments[$pos] = $result[1];
                unset($parameters[$result[0]]);
                continue;
            }

            if ($parameter->isDefaultValueAvailable()) {
                $arguments[$pos] = $parameter->getDefaultValue();
                continue;
            }

            throw UnresolvableArgumentException::fromParameter($parameter);
        }

        return $arguments;
    }

    /**
     * Returns an array of reflection parameters.
     *
     * @return \ReflectionParameter[]
     */
    protected function getParameters() : array
    {
        return $this->reflection->getParameters();
    }

    /**
     * Returns the [key, value] pair if the parameter is matched or null otherwise.
     *
     * @param \ReflectionParameter $parameter
     * @param array                $parameters
     *
     * @return array|null
     */
    abstract protected function match(\ReflectionParameter $parameter, array $parameters) : ?array;
}
