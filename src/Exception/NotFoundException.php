<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\Exception;

/**
 * Exception thrown when the requested resource does not exist (404).
 */
class NotFoundException extends ApiException
{
    public function __construct(
        string $message,
        ?string $endpoint = null,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, 404, $endpoint, $previous);
    }
}
