<?php

namespace App\Common\Services\sms;

use RuntimeException;
use Throwable;

/**
 * Class SMSException
 * @package App\Common\Services\sms
 */
class SMSException extends RuntimeException
{
    public const POSSIBLE_ERRORS = [
        'ERROR 101' => 'Access is restricted, wrong credentials. Check the username and password values.',
        'ERROR 102' => 'Parameters are wrong or missing. Check that all the required parameters are present.',
        'FAILED 102' => 'No delivery report yet, try again in 5 seconds.',
        'FAILED 209' => 'Server failure, try again after a few seconds or try the api3.messente.com backup server.',
    ];

    /**
     * SMSException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            "Error code is : $message and Error Message is : " . self::POSSIBLE_ERRORS[$message],
            $code,
            $previous,
        );
    }
}
