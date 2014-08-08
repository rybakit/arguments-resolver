<?php

$loader = require __DIR__.'/../vendor/autoload.php';
$loader->addPsr4('CallableArgumentsResolver\Tests\\', __DIR__);
$loader->addPsr4('CallableArgumentsResolver\Tests\\', __DIR__.'/callables');

require __DIR__.'/callables/functions.php';
require __DIR__.'/utils.php';
