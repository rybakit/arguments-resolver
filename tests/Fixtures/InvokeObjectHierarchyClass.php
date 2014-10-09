<?php

namespace ArgumentsResolver\Tests\Fixtures;

class InvokeObjectHierarchyClass
{
    public function __invoke($mixed1, \Exception $object1, $mixed2, \RuntimeException $object2)
    {
    }
}
