<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\Tests\Unit\DTO\TextToSpeech;

use PHPUnit\Framework\TestCase;
use YoanBernabeu\MusicGptBundle\DTO\TextToSpeech\TextToSpeechResponse;

/**
 * @covers \YoanBernabeu\MusicGptBundle\DTO\TextToSpeech\TextToSpeechResponse
 */
class TextToSpeechResponseTest extends TestCase
{
    public function testFromArrayCreatesCorrectInstance(): void
    {
        $data = [
            'success' => true,
            'message' => 'Message published to queue',
            'task_id' => '0a65cbb6-2ab8-4949-9ee0-0e8c138ac2cf',
            'conversion_id' => '6542baa6-d61f-4d90-b832-ed929d9c0996',
            'eta' => 17,
            'credit_estimate' => 0.68,
            'status' => 'IN_QUEUE',
        ];

        $response = TextToSpeechResponse::fromArray($data);

        $this->assertTrue($response->isSuccess());
        $this->assertSame('Message published to queue', $response->getMessage());
        $this->assertSame('0a65cbb6-2ab8-4949-9ee0-0e8c138ac2cf', $response->getTaskId());
        $this->assertSame('6542baa6-d61f-4d90-b832-ed929d9c0996', $response->getConversionId());
        $this->assertSame(17, $response->getEta());
        $this->assertSame(0.68, $response->getCreditEstimate());
        $this->assertSame('IN_QUEUE', $response->getStatus());
    }

    public function testFromArrayHandlesMissingOptionalFields(): void
    {
        $data = [
            'success' => true,
            'task_id' => '72eed5b0-8652-4bb4-9a95-eb0ad4850f12',
            'conversion_id' => '648a6823-b2a4-47b3-801e-f452c567ae6f',
        ];

        $response = TextToSpeechResponse::fromArray($data);

        $this->assertTrue($response->isSuccess());
        $this->assertNull($response->getMessage());
        $this->assertSame('72eed5b0-8652-4bb4-9a95-eb0ad4850f12', $response->getTaskId());
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

        $response = TextToSpeechResponse::fromArray($data);

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
            'eta' => '19',  // String instead of int
            'credit_estimate' => '0.68',  // String instead of float
        ];

        $response = TextToSpeechResponse::fromArray($data);

        $this->assertSame(19, $response->getEta());
        $this->assertSame(0.68, $response->getCreditEstimate());
    }
}

