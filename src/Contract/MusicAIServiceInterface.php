<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\Contract;

use YoanBernabeu\MusicGptBundle\DTO\MusicAI\MusicAIRequest;
use YoanBernabeu\MusicGptBundle\DTO\MusicAI\MusicAIResponse;
use YoanBernabeu\MusicGptBundle\Exception\MusicGptException;

/**
 * Interface for Music AI Service.
 *
 * Provides AI-powered music generation from text prompts.
 */
interface MusicAIServiceInterface
{
    /**
     * Generate music from a text prompt using AI.
     *
     * @throws MusicGptException If the request fails
     */
    public function generate(MusicAIRequest $request): MusicAIResponse;
}
