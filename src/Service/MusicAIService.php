<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\Service;

use YoanBernabeu\MusicGptBundle\Contract\MusicAIServiceInterface;
use YoanBernabeu\MusicGptBundle\DTO\MusicAI\MusicAIRequest;
use YoanBernabeu\MusicGptBundle\DTO\MusicAI\MusicAIResponse;

/**
 * Music AI Service.
 *
 * Provides AI-powered music generation from text prompts.
 */
class MusicAIService implements MusicAIServiceInterface
{
    public function __construct(
        private readonly HttpClient $httpClient,
    ) {
    }

    public function generate(MusicAIRequest $request): MusicAIResponse
    {
        return $this->httpClient->sendRequest($request, MusicAIResponse::class);
    }
}

