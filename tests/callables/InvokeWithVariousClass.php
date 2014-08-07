<?php

class InvokeWithVariousClass
{
    public function __invoke($foo, \stdClass $bar, array $baz = [], $qux = null)
    {
    }
}
