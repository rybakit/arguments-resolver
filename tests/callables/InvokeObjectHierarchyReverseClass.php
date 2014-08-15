<?php

namespace CallableArgumentsResolver\Tests;

class InvokeObjectHierarchyReverseClass
{
    public function __invoke($mixed1, \RuntimeException $object1, $mixed2, \Exception $object2)
    {
    }
}
