<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\DTO\MusicAI;

use YoanBernabeu\MusicGptBundle\DTO\AbstractResponse;

/**
 * Response DTO for Music AI generation.
 *
 * @see https://docs.musicgpt.com/api-documentation/conversions/musicai
 */
class MusicAIResponse extends AbstractResponse
{
    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    public function isSuccess(): bool
    {
        return $this->data['success'] ?? false;
    }

    public function getMessage(): string
    {
        return $this->data['message'] ?? '';
    }

    public function getTaskId(): string
    {
        return $this->data['task_id'] ?? '';
    }

    public function getConversionId1(): string
    {
        return $this->data['conversion_id_1'] ?? '';
    }

    public function getConversionId2(): string
    {
        return $this->data['conversion_id_2'] ?? '';
    }

    /**
     * Get both conversion IDs as an array.
     *
     * @return string[]
     */
    public function getConversionIds(): array
    {
        return [
            $this->getConversionId1(),
            $this->getConversionId2(),
        ];
    }

    public function getEta(): int
    {
        return $this->data['eta'] ?? 0;
    }

    public function getCreditEstimate(): ?float
    {
        return isset($this->data['credit_estimate']) ? (float) $this->data['credit_estimate'] : null;
    }
}
