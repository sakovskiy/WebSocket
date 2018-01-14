<?php

namespace Lara\Foundation;

use Illuminate\Container\Container;
use Illuminate\Contracts\Config\Repository;
use Lara\Foundation\Io\Request;
use Psr\Log\LoggerInterface;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;


/**
 * Class ClientRouter
 * @package Lara\Foundation
 */
class WebSocketKernel implements MessageComponentInterface
{
    /**
     * @var Container
     */
    private $app;

    /**
     * @var LoggerInterface
     */
    private $log;

    /**
     * @var ClientsPool
     */
    private $clients;

    /**
     * ClientPool constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->log = $app->make(LoggerInterface::class);

        $config = $app->make(Repository::class);

        $this->clients = $app->make(ClientsPool::class);

        $this->log->info(
            'Client started at ws://' . $config->get('app.host') . ':' . $config->get('app.port')
        );
    }

    /**
     * When a new connection is opened it will be passed to this method
     * @param  ConnectionInterface $conn The socket/connection that just connected to your application
     * @throws \Exception
     */
    public function onOpen(ConnectionInterface $conn)
    {

        $this->clients->attach($conn);
        $this->log->info('Open connection #' . $conn->resourceId);
    }

    /**
     * This is called before or after a socket is closed (depends on how it's closed).  SendMessage to $conn will not result in an error if it has already been closed.
     * @param  ConnectionInterface $conn The socket/connection that is closing/closed
     * @throws \Exception
     */
    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        $this->log->info('Closed connection #' . $conn->resourceId);
    }

    /**
     * If there is an error with one of the sockets, or somewhere in the application where an Exception is thrown,
     * the Exception is sent back down the stack, handled by the Server and bubbled back up the application through this method
     * @param  ConnectionInterface $conn
     * @param  \Exception $e
     * @throws \Exception
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $this->app->throw($e);

        $conn->close();
    }

    /**
     * Triggered when a client sends data through the socket
     * @param  \Ratchet\ConnectionInterface $from The socket/connection that sent the message to your application
     * @param  string $msg The message received
     * @throws \Exception
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        try{
            $this->log->info('Message from #' . $from->resourceId . "\n" . $msg);

            $request = new Request($msg, $from);
        } catch (\Throwable $e){
            $this->onError($from,$e);
        }

    }
}