<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\DTO\MusicAI;

use YoanBernabeu\MusicGptBundle\DTO\AbstractRequest;

/**
 * Request DTO for Music AI generation.
 *
 * @see https://docs.musicgpt.com/api-documentation/conversions/musicai
 */
class MusicAIRequest extends AbstractRequest
{
    public function __construct(
        private readonly ?string $prompt = null,
        private readonly ?string $musicStyle = null,
        private readonly ?string $lyrics = null,
        private readonly bool $makeInstrumental = false,
        private readonly bool $vocalOnly = false,
        private readonly ?string $voiceId = null,
        private readonly ?string $webhookUrl = null,
    ) {
    }

    public function getPrompt(): ?string
    {
        return $this->prompt;
    }

    public function getMusicStyle(): ?string
    {
        return $this->musicStyle;
    }

    public function getLyrics(): ?string
    {
        return $this->lyrics;
    }

    public function isMakeInstrumental(): bool
    {
        return $this->makeInstrumental;
    }

    public function isVocalOnly(): bool
    {
        return $this->vocalOnly;
    }

    public function getVoiceId(): ?string
    {
        return $this->voiceId;
    }

    public function getWebhookUrl(): ?string
    {
        return $this->webhookUrl;
    }

    public function toArray(): array
    {
        $data = [];

        if (null !== $this->prompt) {
            $data['prompt'] = $this->prompt;
        }

        if (null !== $this->musicStyle) {
            $data['music_style'] = $this->musicStyle;
        }

        if (null !== $this->lyrics) {
            $data['lyrics'] = $this->lyrics;
        }

        if ($this->makeInstrumental) {
            $data['make_instrumental'] = $this->makeInstrumental;
        }

        if ($this->vocalOnly) {
            $data['vocal_only'] = $this->vocalOnly;
        }

        if (null !== $this->voiceId) {
            $data['voice_id'] = $this->voiceId;
        }

        if (null !== $this->webhookUrl) {
            $data['webhook_url'] = $this->webhookUrl;
        }

        return $data;
    }

    public function getEndpoint(): string
    {
        return '/MusicAI';
    }
}
