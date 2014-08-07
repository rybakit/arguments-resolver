<?php

function create_callable($type, $mode)
{
    switch ($type) {
        case 'method':
            return [new TestClass(), 'method'.camelize($mode)];

        case 'static_method':
            return ['TestClass', 'staticMethod'.camelize($mode)];

        case 'invoked_method':
            return (new ReflectionClass('Invoke'.camelize($mode).'Class'))->newInstance();

        case 'closure':
            return (new ReflectionFunction('function_'.$mode))->getClosure();

        case 'function':
            return 'function_'.$mode;
    }
}

function camelize($string)
{
    return str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
}
