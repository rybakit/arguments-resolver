<?php

namespace ArgumentsResolver\Tests;

class InvokeCallableClass
{
    public function __invoke($mixed1, callable $callable, $mixed2)
    {
    }
}
