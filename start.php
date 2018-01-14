<?php
require __DIR__ . '/vendor/autoload.php';

$app = new \Lara\Foundation\Application(__DIR__);

$app->registerExceptionHandler(new \App\Exception\ExceptionHandler($app->make('log')));

$app->boot();

