<?php

namespace ArgumentsResolver\Tests;

class InvokeOptionalClass
{
    public function __invoke($mixed1, $mixed2, $optional1 = 1, $optional2 = 2)
    {
    }
}
