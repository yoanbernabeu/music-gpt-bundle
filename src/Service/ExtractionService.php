<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\Service;

use YoanBernabeu\MusicGptBundle\Contract\ExtractionServiceInterface;
use YoanBernabeu\MusicGptBundle\DTO\Extraction\ExtractionRequest;
use YoanBernabeu\MusicGptBundle\DTO\Extraction\ExtractionResponse;

/**
 * Extraction Service.
 *
 * Extracts audio stems (vocals, instrumental, drums, etc.) from audio files.
 */
class ExtractionService implements ExtractionServiceInterface
{
    public function __construct(
        private readonly HttpClient $httpClient,
    ) {
    }

    public function extractStems(ExtractionRequest $request): ExtractionResponse
    {
        return $this->httpClient->sendRequest($request, ExtractionResponse::class);
    }
}
