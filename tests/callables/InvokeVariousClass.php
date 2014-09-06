<?php

namespace ArgumentsResolver\Tests;

class InvokeVariousClass
{
    public function __invoke($foo, \stdClass $bar, array $baz = [], $qux = null)
    {
    }
}
