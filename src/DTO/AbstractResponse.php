<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\DTO;

/**
 * Abstract base class for API response DTOs.
 */
abstract class AbstractResponse implements ResponseInterface
{
    /**
     * @param array<string, mixed> $data
     */
    public function __construct(
        protected readonly array $data
    ) {
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
