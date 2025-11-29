<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\Service;

use YoanBernabeu\MusicGptBundle\Contract\TextToSpeechServiceInterface;
use YoanBernabeu\MusicGptBundle\DTO\TextToSpeech\TextToSpeechRequest;
use YoanBernabeu\MusicGptBundle\DTO\TextToSpeech\TextToSpeechResponse;

/**
 * Text To Speech Service.
 *
 * Converts text to speech using specified voice models.
 */
class TextToSpeechService implements TextToSpeechServiceInterface
{
    public function __construct(
        private readonly HttpClient $httpClient,
    ) {
    }

    public function createTextToSpeech(TextToSpeechRequest $request): TextToSpeechResponse
    {
        return $this->httpClient->sendRequest($request, TextToSpeechResponse::class);
    }
}

