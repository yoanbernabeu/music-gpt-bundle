<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\Exception;

/**
 * Exception thrown when there is a conflict with the current state of the resource (409).
 */
class ConflictException extends ApiException
{
    public function __construct(
        string $message,
        ?string $endpoint = null,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, 409, $endpoint, $previous);
    }
}
