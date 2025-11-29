<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\DTO\Extraction;

use YoanBernabeu\MusicGptBundle\DTO\AbstractResponse;

/**
 * Response DTO for Audio Extraction.
 *
 * @see https://docs.musicgpt.com/api-documentation/endpoint/extraction
 */
class ExtractionResponse extends AbstractResponse
{
    public function __construct(
        private readonly bool $success,
        private readonly ?string $message = null,
        private readonly ?string $taskId = null,
        private readonly ?string $conversionId = null,
        private readonly ?int $eta = null,
        private readonly ?float $creditEstimate = null,
        private readonly ?string $status = null,
    ) {
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function getTaskId(): ?string
    {
        return $this->taskId;
    }

    public function getConversionId(): ?string
    {
        return $this->conversionId;
    }

    public function getEta(): ?int
    {
        return $this->eta;
    }

    public function getCreditEstimate(): ?float
    {
        return $this->creditEstimate;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            success: $data['success'] ?? false,
            message: $data['message'] ?? null,
            taskId: $data['task_id'] ?? null,
            conversionId: $data['conversion_id'] ?? null,
            eta: isset($data['eta']) ? (int) $data['eta'] : null,
            creditEstimate: isset($data['credit_estimate']) ? (float) $data['credit_estimate'] : null,
            status: $data['status'] ?? null,
        );
    }
}

