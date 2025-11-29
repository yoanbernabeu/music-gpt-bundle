<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\Tests\Unit\DTO\MusicAI;

use PHPUnit\Framework\TestCase;
use YoanBernabeu\MusicGptBundle\DTO\MusicAI\MusicAIResponse;

/**
 * @covers \YoanBernabeu\MusicGptBundle\DTO\MusicAI\MusicAIResponse
 */
class MusicAIResponseTest extends TestCase
{
    public function testFromArrayWithAllFields(): void
    {
        $data = [
            'success' => true,
            'message' => 'Message published to queue',
            'task_id' => '8e058b85-6c22-41cc-a6ed-1e91ed73e34b',
            'conversion_id_1' => '2872d9a6-4abe-4a8f-a04f-5540c4ef0a1a',
            'conversion_id_2' => 'dda98922-d5e9-4fc9-accd-4fc1f0729234',
            'eta' => 76,
            'credit_estimate' => 0.99,
        ];

        $response = MusicAIResponse::fromArray($data);

        $this->assertTrue($response->isSuccess());
        $this->assertSame('Message published to queue', $response->getMessage());
        $this->assertSame('8e058b85-6c22-41cc-a6ed-1e91ed73e34b', $response->getTaskId());
        $this->assertSame('2872d9a6-4abe-4a8f-a04f-5540c4ef0a1a', $response->getConversionId1());
        $this->assertSame('dda98922-d5e9-4fc9-accd-4fc1f0729234', $response->getConversionId2());
        $this->assertSame(76, $response->getEta());
        $this->assertSame(0.99, $response->getCreditEstimate());
    }

    public function testFromArrayWithoutCreditEstimate(): void
    {
        $data = [
            'success' => true,
            'message' => 'Test message',
            'task_id' => 'task-123',
            'conversion_id_1' => 'conv-1',
            'conversion_id_2' => 'conv-2',
            'eta' => 100,
        ];

        $response = MusicAIResponse::fromArray($data);

        $this->assertTrue($response->isSuccess());
        $this->assertNull($response->getCreditEstimate());
    }

    public function testToArray(): void
    {
        $data = [
            'success' => true,
            'message' => 'Test',
            'task_id' => 'task-id',
            'conversion_id_1' => 'conv-1',
            'conversion_id_2' => 'conv-2',
            'eta' => 50,
            'credit_estimate' => 1.5,
        ];

        $response = MusicAIResponse::fromArray($data);
        $array = $response->toArray();

        $this->assertSame($data, $array);
    }

    public function testGetConversionIds(): void
    {
        $data = [
            'success' => true,
            'message' => 'Test',
            'task_id' => 'task-id',
            'conversion_id_1' => 'first-id',
            'conversion_id_2' => 'second-id',
            'eta' => 50,
        ];

        $response = MusicAIResponse::fromArray($data);
        $ids = $response->getConversionIds();

        $this->assertCount(2, $ids);
        $this->assertSame('first-id', $ids[0]);
        $this->assertSame('second-id', $ids[1]);
    }
}
