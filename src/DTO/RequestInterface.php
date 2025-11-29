<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\DTO;

/**
 * Interface for API request DTOs.
 */
interface RequestInterface
{
    /**
     * Convert the request to an array for API submission.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array;

    /**
     * Get the API endpoint path for this request.
     */
    public function getEndpoint(): string;

    /**
     * Get the HTTP method for this request.
     */
    public function getMethod(): string;
}
