<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\DTO\Cover;

use YoanBernabeu\MusicGptBundle\DTO\AbstractRequest;

/**
 * Request DTO for Cover Song generation.
 *
 * @see https://docs.musicgpt.com/api-documentation/conversions/cover
 */
class CoverRequest extends AbstractRequest
{
    public function __construct(
        private readonly ?string $audioUrl = null,
        private readonly ?string $audioFile = null,
        private readonly ?string $voiceId = null,
        private readonly int $pitch = 0,
        private readonly ?string $webhookUrl = null,
    ) {
    }

    public function getAudioUrl(): ?string
    {
        return $this->audioUrl;
    }

    public function getAudioFile(): ?string
    {
        return $this->audioFile;
    }

    public function getVoiceId(): ?string
    {
        return $this->voiceId;
    }

    public function getPitch(): int
    {
        return $this->pitch;
    }

    public function getWebhookUrl(): ?string
    {
        return $this->webhookUrl;
    }

    public function getEndpoint(): string
    {
        return '/Cover';
    }

    public function getMethod(): string
    {
        return 'POST';
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [];

        if (null !== $this->audioUrl) {
            $data['audio_url'] = $this->audioUrl;
        }

        if (null !== $this->audioFile) {
            $data['audio_file'] = $this->audioFile;
        }

        if (null !== $this->voiceId) {
            $data['voice_id'] = $this->voiceId;
        }

        if (0 !== $this->pitch) {
            $data['pitch'] = $this->pitch;
        }

        if (null !== $this->webhookUrl) {
            $data['webhook_url'] = $this->webhookUrl;
        }

        return $data;
    }
}
