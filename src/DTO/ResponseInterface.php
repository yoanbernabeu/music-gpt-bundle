<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\DTO;

/**
 * Interface for API response DTOs.
 */
interface ResponseInterface
{
    /**
     * Create a response DTO from API response data.
     *
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self;

    /**
     * Convert the response back to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
