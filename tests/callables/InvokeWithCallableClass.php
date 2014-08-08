<?php

namespace CallableArgumentsResolver\Tests;

class InvokeWithCallableClass
{
    public function __invoke($mixed1, callable $callable, $mixed2)
    {
    }
}
