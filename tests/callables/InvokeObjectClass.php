<?php

namespace CallableArgumentsResolver\Tests;

class InvokeObjectClass
{
    public function __invoke($mixed1, \stdClass $object, $mixed2)
    {
    }
}
