<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\Exception;

/**
 * Exception thrown when account has insufficient funds (402).
 */
class PaymentRequiredException extends ApiException
{
    public function __construct(
        string $message = 'Your account has insufficient funds to process the request',
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, 402, null, $previous);
    }
}
