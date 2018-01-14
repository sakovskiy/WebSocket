<?php

namespace Lara\Foundation\Io;


use Ratchet\ConnectionInterface;

/**
 * Class Request
 * @package Lara\Foundation\Io
 */
class Request
{
    /**
     * @var string
     */
    private $message;
    /**
     * @var ConnectionInterface
     */
    private $conn;

    /**
     * Request constructor.
     * @param string $message
     * @param ConnectionInterface $conn
     */
    public function __construct(string $message, ConnectionInterface $conn)
    {
        $this->message = $message;
        $this->conn = $conn;
    }
}