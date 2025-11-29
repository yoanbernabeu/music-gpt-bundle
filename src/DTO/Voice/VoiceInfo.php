<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\DTO\Voice;

/**
 * Voice information DTO.
 *
 * @see https://docs.musicgpt.com/api-documentation/endpoint/searchVoices
 * @see https://docs.musicgpt.com/api-documentation/endpoint/getAllVoices
 */
class VoiceInfo
{
    public function __construct(
        private readonly string $voiceId,
        private readonly string $voiceName,
    ) {
    }

    public function getVoiceId(): string
    {
        return $this->voiceId;
    }

    public function getVoiceName(): string
    {
        return $this->voiceName;
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            voiceId: $data['voice_id'] ?? '',
            voiceName: $data['voice_name'] ?? '',
        );
    }
}

