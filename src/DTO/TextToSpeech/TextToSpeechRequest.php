<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\DTO\TextToSpeech;

use YoanBernabeu\MusicGptBundle\DTO\AbstractRequest;

/**
 * Request DTO for Text To Speech generation.
 *
 * @see https://docs.musicgpt.com/api-documentation/conversions/texttospeech
 */
class TextToSpeechRequest extends AbstractRequest
{
    public function __construct(
        private readonly string $text,
        private readonly string $gender,
        private readonly ?string $voiceId = null,
        private readonly ?string $sampleAudioUrl = null,
        private readonly ?string $webhookUrl = null,
    ) {
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function getVoiceId(): ?string
    {
        return $this->voiceId;
    }

    public function getSampleAudioUrl(): ?string
    {
        return $this->sampleAudioUrl;
    }

    public function getWebhookUrl(): ?string
    {
        return $this->webhookUrl;
    }

    public function getEndpoint(): string
    {
        return '/TextToSpeech';
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
        $data = [
            'text' => $this->text,
            'gender' => $this->gender,
        ];

        if (null !== $this->voiceId) {
            $data['voice_id'] = $this->voiceId;
        }

        if (null !== $this->sampleAudioUrl) {
            $data['sample_audio_url'] = $this->sampleAudioUrl;
        }

        if (null !== $this->webhookUrl) {
            $data['webhook_url'] = $this->webhookUrl;
        }

        return $data;
    }
}

