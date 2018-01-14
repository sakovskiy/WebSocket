<?php
return [
    'host' => '127.0.0.1',
    'port' => '8080',
    'router' => \App\WebSocket\Kernel::class,

    'providers' => [
        \Lara\Foundation\ServerServiceProvider::class
    ]
];