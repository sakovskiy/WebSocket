<?php

namespace Lara\WebSocket;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Lara\Foundation\Application;

/**
 * Class ServiceProvider
 * @package Lara\WebSocked
 */
class ServiceProvider extends BaseServiceProvider
{
    public function boot(Application $application, Repository $repository)
    {


    }
}