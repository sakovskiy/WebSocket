<?php

namespace App\Exception;

use Lara\Foundation\ExceptionHandler as ExceptionHandlerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class ExceptionHandler
 * @package App\Exception
 */
class ExceptionHandler implements ExceptionHandlerInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * ExceptionHandler constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param \Throwable $e
     */
    public function handle(\Throwable $e)
    {
        $this->logger->error(
            $e->getFile() . ':' . $e->getLine() . "\n" .
            $e->getMessage() . "\n" .
            $e->getTraceAsString()
        );
    }
}