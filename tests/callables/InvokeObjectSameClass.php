<?php

namespace ArgumentsResolver\Tests;

class InvokeObjectSameClass
{
    public function __invoke($mixed1, \stdClass $object, $mixed2, \stdClass $object2)
    {
    }
}
