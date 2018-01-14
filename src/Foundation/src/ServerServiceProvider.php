<?php

namespace Lara\Foundation;

use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\LoopInterface;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;


/**
 * Class ServerServiceProvider
 * @package Lara\Foundation
 */
class ServerServiceProvider extends BaseServiceProvider
{
    /**
     * @return  void
     */
    public function register()
    {
        $this->app->singleton(IoServer::class, function (Container $app) {
            $config = $app->make(Repository::class);

            $pool = $app->make($config->get('app.router'));
            return IoServer::factory(
                new HttpServer(
                    new WsServer($pool)
                ),
                $config->get('app.port'),
                $config->get('app.host')
            );
        });

        $this->app->singleton(LoopInterface::class, function (Container $app) {
            return $app->make(IoServer::class)->loop;
        });

        $this->app->instance(ClientsPool::class, new ClientsPool());
    }

    /**
     * @return void
     */
    public function boot()
    {
        $this->app->make(IoServer::class)->run();
    }
}