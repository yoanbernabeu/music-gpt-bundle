<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\DTO\Voice;

use YoanBernabeu\MusicGptBundle\DTO\AbstractResponse;

/**
 * Response DTO for voice listings.
 *
 * @see https://docs.musicgpt.com/api-documentation/endpoint/searchVoices
 * @see https://docs.musicgpt.com/api-documentation/endpoint/getAllVoices
 */
class VoicesResponse extends AbstractResponse
{
    /**
     * @param array<VoiceInfo> $voices
     */
    public function __construct(
        private readonly bool $success,
        private readonly array $voices,
        private readonly int $limit,
        private readonly int $page,
        private readonly int $total,
        private readonly ?string $message = null,
    ) {
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @return array<VoiceInfo>
     */
    public function getVoices(): array
    {
        return $this->voices;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $voices = [];
        if (isset($data['voices']) && is_array($data['voices'])) {
            foreach ($data['voices'] as $voiceData) {
                $voices[] = VoiceInfo::fromArray($voiceData);
            }
        }

        return new self(
            success: $data['success'] ?? false,
            voices: $voices,
            limit: $data['limit'] ?? 20,
            page: $data['page'] ?? 0,
            total: $data['total'] ?? 0,
            message: $data['message'] ?? null,
        );
    }
}
