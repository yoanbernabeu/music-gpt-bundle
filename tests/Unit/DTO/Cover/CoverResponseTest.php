<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\Tests\Unit\DTO\Cover;

use PHPUnit\Framework\TestCase;
use YoanBernabeu\MusicGptBundle\DTO\Cover\CoverResponse;

/**
 * @covers \YoanBernabeu\MusicGptBundle\DTO\Cover\CoverResponse
 */
class CoverResponseTest extends TestCase
{
    public function testFromArrayCreatesCorrectInstance(): void
    {
        $data = [
            'success' => true,
            'message' => 'Message published to queue',
            'task_id' => 'task-123',
            'conversion_id' => 'conv-456',
            'eta' => 33,
            'credit_estimate' => 1.5,
            'status' => 'IN_QUEUE',
        ];

        $response = CoverResponse::fromArray($data);

        $this->assertTrue($response->isSuccess());
        $this->assertSame('Message published to queue', $response->getMessage());
        $this->assertSame('task-123', $response->getTaskId());
        $this->assertSame('conv-456', $response->getConversionId());
        $this->assertSame(33, $response->getEta());
        $this->assertSame(1.5, $response->getCreditEstimate());
        $this->assertSame('IN_QUEUE', $response->getStatus());
    }

    public function testFromArrayHandlesMissingOptionalFields(): void
    {
        $data = [
            'success' => true,
            'task_id' => 'task-789',
            'conversion_id' => 'conv-789',
        ];

        $response = CoverResponse::fromArray($data);

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
            'message' => 'Invalid voice ID',
        ];

        $response = CoverResponse::fromArray($data);

        $this->assertFalse($response->isSuccess());
        $this->assertSame('Invalid voice ID', $response->getMessage());
        $this->assertNull($response->getTaskId());
    }

    public function testFromArrayConvertsTypesCorrectly(): void
    {
        $data = [
            'success' => true,
            'task_id' => 'task-456',
            'conversion_id' => 'conv-456',
            'eta' => '60',  // String instead of int
            'credit_estimate' => '2.75',  // String instead of float
        ];

        $response = CoverResponse::fromArray($data);

        $this->assertSame(60, $response->getEta());
        $this->assertSame(2.75, $response->getCreditEstimate());
    }
}
