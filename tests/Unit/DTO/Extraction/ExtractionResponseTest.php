<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\Tests\Unit\DTO\Extraction;

use PHPUnit\Framework\TestCase;
use YoanBernabeu\MusicGptBundle\DTO\Extraction\ExtractionResponse;

/**
 * @covers \YoanBernabeu\MusicGptBundle\DTO\Extraction\ExtractionResponse
 */
class ExtractionResponseTest extends TestCase
{
    public function testFromArrayCreatesCorrectInstance(): void
    {
        $data = [
            'success' => true,
            'message' => 'Message published to queue',
            'task_id' => '62725d68-01e8-4c87-8fb0-298aa81c529c',
            'conversion_id' => '46b358c9-b22f-49d1-a68d-17901a6a549b',
            'eta' => 11,
            'credit_estimate' => 2.5,
            'status' => 'IN_QUEUE',
        ];

        $response = ExtractionResponse::fromArray($data);

        $this->assertTrue($response->isSuccess());
        $this->assertSame('Message published to queue', $response->getMessage());
        $this->assertSame('62725d68-01e8-4c87-8fb0-298aa81c529c', $response->getTaskId());
        $this->assertSame('46b358c9-b22f-49d1-a68d-17901a6a549b', $response->getConversionId());
        $this->assertSame(11, $response->getEta());
        $this->assertSame(2.5, $response->getCreditEstimate());
        $this->assertSame('IN_QUEUE', $response->getStatus());
    }

    public function testFromArrayHandlesMissingOptionalFields(): void
    {
        $data = [
            'success' => true,
            'task_id' => 'task-789',
            'conversion_id' => 'conv-789',
        ];

        $response = ExtractionResponse::fromArray($data);

        $this->assertTrue($response->isSuccess());
        $this->assertNull($response->getMessage());
        $this->assertSame('task-789', $response->getTaskId());
        $this->assertNull($response->getEta());
        $this->assertNull($response->getCreditEstimate());
        $this->assertNull($response->getStatus());
    }

    public function testFromArrayHandlesFailureResponse(): void
    {
        $data = [
            'success' => false,
            'message' => 'Invalid audio format',
        ];

        $response = ExtractionResponse::fromArray($data);

        $this->assertFalse($response->isSuccess());
        $this->assertSame('Invalid audio format', $response->getMessage());
        $this->assertNull($response->getTaskId());
    }

    public function testFromArrayConvertsTypesCorrectly(): void
    {
        $data = [
            'success' => true,
            'task_id' => 'task-456',
            'conversion_id' => 'conv-456',
            'eta' => '15',  // String instead of int
            'credit_estimate' => '3.75',  // String instead of float
        ];

        $response = ExtractionResponse::fromArray($data);

        $this->assertSame(15, $response->getEta());
        $this->assertSame(3.75, $response->getCreditEstimate());
    }
}

