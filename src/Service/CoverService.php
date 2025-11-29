<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\Service;

use YoanBernabeu\MusicGptBundle\Contract\CoverServiceInterface;
use YoanBernabeu\MusicGptBundle\DTO\Cover\CoverRequest;
use YoanBernabeu\MusicGptBundle\DTO\Cover\CoverResponse;

/**
 * Cover Service.
 *
 * Converts audio to cover songs using different voice models.
 */
class CoverService implements CoverServiceInterface
{
    public function __construct(
        private readonly HttpClient $httpClient,
    ) {
    }

    public function createCover(CoverRequest $request): CoverResponse
    {
        return $this->httpClient->sendRequest($request, CoverResponse::class);
    }
}
