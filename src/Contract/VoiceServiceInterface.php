<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\Contract;

use YoanBernabeu\MusicGptBundle\DTO\Voice\VoicesResponse;
use YoanBernabeu\MusicGptBundle\Exception\MusicGptException;

/**
 * Interface for Voice Service.
 *
 * Manages voice listings and search functionality.
 */
interface VoiceServiceInterface
{
    /**
     * Search for voices by name.
     *
     * @throws MusicGptException If the request fails
     */
    public function searchVoices(string $query, int $limit = 20, int $page = 0): VoicesResponse;

    /**
     * Get all available voices.
     *
     * @throws MusicGptException If the request fails
     */
    public function getAllVoices(int $limit = 20, int $page = 0): VoicesResponse;
}
