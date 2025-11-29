<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\DTO;

/**
 * Abstract base class for API request DTOs.
 */
abstract class AbstractRequest implements RequestInterface
{
    public function getMethod(): string
    {
        return 'POST';
    }
}
