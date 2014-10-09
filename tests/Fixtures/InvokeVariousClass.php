<?php

namespace ArgumentsResolver\Tests\Fixtures;

class InvokeVariousClass
{
    public function __invoke($foo, \stdClass $bar, array $baz = [], $qux = null)
    {
    }
}
