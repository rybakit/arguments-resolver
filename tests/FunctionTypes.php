<?php

namespace ArgumentsResolver\Tests;

class FunctionTypes
{
    const METHOD            = 'method';
    const STATIC_METHOD     = 'static_method';
    const INVOKED_METHOD    = 'invoked_method';
    const CLOSURE           = 'closure';
    const FUNC              = 'function';

    public static function getAll()
    {
        return [
            self::METHOD,
            self::STATIC_METHOD,
            self::INVOKED_METHOD,
            self::CLOSURE,
            self::FUNC,
        ];
    }
}
