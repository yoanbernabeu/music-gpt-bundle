<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\Contract;

use YoanBernabeu\MusicGptBundle\DTO\Extraction\ExtractionRequest;
use YoanBernabeu\MusicGptBundle\DTO\Extraction\ExtractionResponse;
use YoanBernabeu\MusicGptBundle\Exception\MusicGptException;

/**
 * Interface for Extraction Service.
 *
 * Extracts audio stems (vocals, instrumental, drums, etc.) from audio files.
 */
interface ExtractionServiceInterface
{
    /**
     * Extract audio stems from an audio file or URL.
     *
     * @throws MusicGptException If the request fails
     */
    public function extractStems(ExtractionRequest $request): ExtractionResponse;
}

