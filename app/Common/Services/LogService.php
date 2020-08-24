<?php

namespace App\Common\Services;

use Illuminate\Log\Logger;

class LogService
{
    /**
     * @var Logger
     */
    private Logger $logger;

    /**
     * LogService constructor.
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param string $method
     * @param string $message
     * @param array $context
     */
    public function log($method = 'info', $message = '', $context = [])
    {
        $this->logger->{$method}($message, $context);
    }
}
