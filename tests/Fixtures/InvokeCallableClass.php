<?php

namespace ArgumentsResolver\Tests\Fixtures;

class InvokeCallableClass
{
    public function __invoke($mixed1, callable $callable, $mixed2)
    {
    }
}
