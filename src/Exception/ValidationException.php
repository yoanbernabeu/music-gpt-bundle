<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\Exception;

/**
 * Exception thrown when request validation fails.
 */
class ValidationException extends MusicGptException
{
    /**
     * @param array<string, string[]> $errors
     */
    public function __construct(
        string $message,
        private readonly array $errors = [],
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
    }

    /**
     * @return array<string, string[]>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
