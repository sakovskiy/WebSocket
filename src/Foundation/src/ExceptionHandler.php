<?php

namespace Lara\Foundation;


/**
 * Interface ExceptionHandler
 * @package Lara\Foundation
 */
interface ExceptionHandler
{
    /**
     * @param \Throwable $e
     * @return mixed
     */
    public function handle(\Throwable $e);
}