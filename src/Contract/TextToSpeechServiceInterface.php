<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\Contract;

use YoanBernabeu\MusicGptBundle\DTO\TextToSpeech\TextToSpeechRequest;
use YoanBernabeu\MusicGptBundle\DTO\TextToSpeech\TextToSpeechResponse;
use YoanBernabeu\MusicGptBundle\Exception\MusicGptException;

/**
 * Interface for Text To Speech Service.
 *
 * Converts text to speech using specified voice models.
 */
interface TextToSpeechServiceInterface
{
    /**
     * Create a text to speech audio from text.
     *
     * @throws MusicGptException If the request fails
     */
    public function createTextToSpeech(TextToSpeechRequest $request): TextToSpeechResponse;
}

