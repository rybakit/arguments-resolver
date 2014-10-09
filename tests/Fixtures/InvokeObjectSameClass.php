<?php

namespace ArgumentsResolver\Tests\Fixtures;

class InvokeObjectSameClass
{
    public function __invoke($mixed1, \stdClass $object, $mixed2, \stdClass $object2)
    {
    }
}
