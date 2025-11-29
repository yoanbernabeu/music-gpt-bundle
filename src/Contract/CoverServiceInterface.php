<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\Contract;

use YoanBernabeu\MusicGptBundle\DTO\Cover\CoverRequest;
use YoanBernabeu\MusicGptBundle\DTO\Cover\CoverResponse;
use YoanBernabeu\MusicGptBundle\Exception\MusicGptException;

/**
 * Interface for Cover Service.
 *
 * Converts audio to cover songs using different voice models.
 */
interface CoverServiceInterface
{
    /**
     * Create a cover song from an audio file or URL.
     *
     * @throws MusicGptException If the request fails
     */
    public function createCover(CoverRequest $request): CoverResponse;
}

