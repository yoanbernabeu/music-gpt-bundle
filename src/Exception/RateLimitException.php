<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\Exception;

/**
 * Exception thrown when rate limit is exceeded.
 */
class RateLimitException extends ApiException
{
    public function __construct(
        string $message = 'Rate limit exceeded',
        private readonly ?int $retryAfter = null,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, 429, null, $previous);
    }

    public function getRetryAfter(): ?int
    {
        return $this->retryAfter;
    }
}
