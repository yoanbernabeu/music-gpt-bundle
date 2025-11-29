<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\DTO\Extraction;

use YoanBernabeu\MusicGptBundle\DTO\AbstractRequest;

/**
 * Request DTO for Audio Extraction.
 *
 * @see https://docs.musicgpt.com/api-documentation/endpoint/extraction
 */
class ExtractionRequest extends AbstractRequest
{
    /**
     * @param array<string> $stems
     * @param array<string> $preprocessingOptions
     */
    public function __construct(
        private readonly ?string $audioUrl = null,
        private readonly ?string $audioFile = null,
        private readonly array $stems = [],
        private readonly array $preprocessingOptions = [],
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

    /**
     * @return array<string>
     */
    public function getStems(): array
    {
        return $this->stems;
    }

    /**
     * @return array<string>
     */
    public function getPreprocessingOptions(): array
    {
        return $this->preprocessingOptions;
    }

    public function getWebhookUrl(): ?string
    {
        return $this->webhookUrl;
    }

    public function getEndpoint(): string
    {
        return '/Extraction';
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

        if ([] !== $this->stems) {
            $data['stems'] = $this->stems;
        }

        if ([] !== $this->preprocessingOptions) {
            $data['preprocessing_options'] = $this->preprocessingOptions;
        }

        if (null !== $this->webhookUrl) {
            $data['webhook_url'] = $this->webhookUrl;
        }

        return $data;
    }
}

