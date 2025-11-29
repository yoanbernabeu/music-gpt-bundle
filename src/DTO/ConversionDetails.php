<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\DTO;

/**
 * DTO for conversion details retrieved by ID.
 *
 * @see https://docs.musicgpt.com/api-documentation/endpoint/getById
 */
class ConversionDetails
{
    /**
     * @param array<string, mixed> $data
     */
    public function __construct(
        private readonly array $data
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self($data['conversion'] ?? $data);
    }

    public function isSuccess(): bool
    {
        return $this->data['success'] ?? true;
    }

    public function getTaskId(): string
    {
        return $this->data['task_id'] ?? '';
    }

    public function getConversionId(): string
    {
        return $this->data['conversion_id'] ?? '';
    }

    public function getStatus(): string
    {
        return $this->data['status'] ?? '';
    }

    public function isCompleted(): bool
    {
        return 'COMPLETED' === $this->getStatus();
    }

    public function isFailed(): bool
    {
        return in_array($this->getStatus(), ['FAILED', 'ERROR'], true);
    }

    public function isProcessing(): bool
    {
        return in_array($this->getStatus(), ['PENDING', 'PROCESSING', 'QUEUED'], true);
    }

    public function getStatusMessage(): string
    {
        return $this->data['status_msg'] ?? '';
    }

    public function getAudioUrl(): ?string
    {
        return $this->data['audio_url'] ?? $this->data['conversion_path'] ?? null;
    }

    public function getVideoUrl(): ?string
    {
        return $this->data['video_url'] ?? null;
    }

    public function getImageUrl(): ?string
    {
        return $this->data['image_url'] ?? $this->data['album_cover_path'] ?? null;
    }

    /**
     * Get audio URL for conversion 1 (Music AI).
     */
    public function getAudioUrl1(): ?string
    {
        return $this->data['conversion_path_1'] ?? null;
    }

    /**
     * Get audio URL for conversion 2 (Music AI).
     */
    public function getAudioUrl2(): ?string
    {
        return $this->data['conversion_path_2'] ?? null;
    }

    /**
     * Get WAV audio URL for conversion 1 (Music AI - High Quality).
     */
    public function getAudioWavUrl1(): ?string
    {
        return $this->data['conversion_path_wav_1'] ?? null;
    }

    /**
     * Get WAV audio URL for conversion 2 (Music AI - High Quality).
     */
    public function getAudioWavUrl2(): ?string
    {
        return $this->data['conversion_path_wav_2'] ?? null;
    }

    /**
     * Get album cover thumbnail URL.
     */
    public function getThumbnailUrl(): ?string
    {
        return $this->data['album_cover_thumbnail'] ?? null;
    }

    /**
     * Get title for conversion 1 (Music AI).
     */
    public function getTitle1(): ?string
    {
        return $this->data['title_1'] ?? null;
    }

    /**
     * Get title for conversion 2 (Music AI).
     */
    public function getTitle2(): ?string
    {
        return $this->data['title_2'] ?? null;
    }

    public function getConversionCost(): ?float
    {
        return isset($this->data['conversion_cost']) ? (float) $this->data['conversion_cost'] : null;
    }

    public function getTitle(): ?string
    {
        return $this->data['title'] ?? null;
    }

    public function getLyrics(): ?string
    {
        return $this->data['lyrics'] ?? null;
    }

    public function getMusicStyle(): ?string
    {
        return $this->data['music_style'] ?? null;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        if (isset($this->data['createdAt'])) {
            return new \DateTimeImmutable($this->data['createdAt']);
        }

        return null;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        if (isset($this->data['updatedAt'])) {
            return new \DateTimeImmutable($this->data['updatedAt']);
        }

        return null;
    }

    public function getDuration(): ?int
    {
        return isset($this->data['duration']) ? (int) $this->data['duration'] : null;
    }

    /**
     * @return array<int, string>
     */
    public function getTags(): array
    {
        return $this->data['tags'] ?? [];
    }

    /**
     * Get all raw data.
     *
     * @return array<string, mixed>
     */
    public function getRawData(): array
    {
        return $this->data;
    }
}
