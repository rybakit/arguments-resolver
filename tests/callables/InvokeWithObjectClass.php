<?php

namespace CallableArgumentsResolver\Tests;

class InvokeWithObjectClass
{
    public function __invoke($mixed1, \stdClass $object, $mixed2)
    {
    }
}
