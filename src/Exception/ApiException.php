<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\Exception;

/**
 * Exception thrown when API request fails.
 */
class ApiException extends MusicGptException
{
    public function __construct(
        string $message,
        private readonly int $statusCode = 0,
        private readonly ?string $endpoint = null,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $statusCode, $previous);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getEndpoint(): ?string
    {
        return $this->endpoint;
    }
}
