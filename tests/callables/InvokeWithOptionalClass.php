<?php

namespace CallableArgumentsResolver\Tests;

class InvokeWithOptionalClass
{
    public function __invoke($mixed1, $mixed2, $optional1 = 1, $optional1 = 2)
    {
    }
}
