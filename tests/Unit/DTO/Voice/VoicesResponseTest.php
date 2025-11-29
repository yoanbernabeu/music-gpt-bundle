<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\Tests\Unit\DTO\Voice;

use PHPUnit\Framework\TestCase;
use YoanBernabeu\MusicGptBundle\DTO\Voice\VoiceInfo;
use YoanBernabeu\MusicGptBundle\DTO\Voice\VoicesResponse;

/**
 * @covers \YoanBernabeu\MusicGptBundle\DTO\Voice\VoicesResponse
 */
class VoicesResponseTest extends TestCase
{
    public function testFromArrayCreatesCorrectInstance(): void
    {
        $data = [
            'success' => true,
            'voices' => [
                ['voice_id' => 'JustinBieber', 'voice_name' => 'Justin Bieber'],
                ['voice_id' => 'Drake', 'voice_name' => 'Drake'],
            ],
            'limit' => 20,
            'page' => 0,
            'total' => 2,
        ];

        $response = VoicesResponse::fromArray($data);

        $this->assertTrue($response->isSuccess());
        $this->assertCount(2, $response->getVoices());
        $this->assertSame(20, $response->getLimit());
        $this->assertSame(0, $response->getPage());
        $this->assertSame(2, $response->getTotal());
        $this->assertNull($response->getMessage());
    }

    public function testFromArrayCreatesVoiceInfoInstances(): void
    {
        $data = [
            'success' => true,
            'voices' => [
                ['voice_id' => '00126f62-1f31-434a-abc6-a5e958a737e3', 'voice_name' => 'Joji'],
                ['voice_id' => '0031cf05-6d3d-4c15-9115-d8236590b957', 'voice_name' => 'Amy Winehouse'],
            ],
            'limit' => 20,
            'page' => 0,
            'total' => 3108,
        ];

        $response = VoicesResponse::fromArray($data);
        $voices = $response->getVoices();

        $this->assertCount(2, $voices);
        $this->assertInstanceOf(VoiceInfo::class, $voices[0]);
        $this->assertInstanceOf(VoiceInfo::class, $voices[1]);
        $this->assertSame('00126f62-1f31-434a-abc6-a5e958a737e3', $voices[0]->getVoiceId());
        $this->assertSame('Joji', $voices[0]->getVoiceName());
        $this->assertSame('0031cf05-6d3d-4c15-9115-d8236590b957', $voices[1]->getVoiceId());
        $this->assertSame('Amy Winehouse', $voices[1]->getVoiceName());
    }

    public function testFromArrayHandlesEmptyVoices(): void
    {
        $data = [
            'success' => true,
            'voices' => [],
            'limit' => 20,
            'page' => 0,
            'total' => 0,
        ];

        $response = VoicesResponse::fromArray($data);

        $this->assertTrue($response->isSuccess());
        $this->assertCount(0, $response->getVoices());
        $this->assertSame(0, $response->getTotal());
    }

    public function testFromArrayHandlesMissingVoices(): void
    {
        $data = [
            'success' => true,
            'limit' => 20,
            'page' => 0,
            'total' => 0,
        ];

        $response = VoicesResponse::fromArray($data);

        $this->assertTrue($response->isSuccess());
        $this->assertCount(0, $response->getVoices());
    }

    public function testFromArrayHandlesPagination(): void
    {
        $data = [
            'success' => true,
            'voices' => [
                ['voice_id' => 'voice1', 'voice_name' => 'Voice 1'],
            ],
            'limit' => 50,
            'page' => 2,
            'total' => 3108,
        ];

        $response = VoicesResponse::fromArray($data);

        $this->assertSame(50, $response->getLimit());
        $this->assertSame(2, $response->getPage());
        $this->assertSame(3108, $response->getTotal());
    }

    public function testFromArrayHandlesFailureResponse(): void
    {
        $data = [
            'success' => false,
            'message' => 'Invalid query',
            'voices' => [],
            'limit' => 20,
            'page' => 0,
            'total' => 0,
        ];

        $response = VoicesResponse::fromArray($data);

        $this->assertFalse($response->isSuccess());
        $this->assertSame('Invalid query', $response->getMessage());
    }

    public function testFromArrayHandlesDefaultValues(): void
    {
        $data = [
            'success' => true,
        ];

        $response = VoicesResponse::fromArray($data);

        $this->assertTrue($response->isSuccess());
        $this->assertSame(20, $response->getLimit());
        $this->assertSame(0, $response->getPage());
        $this->assertSame(0, $response->getTotal());
    }
}
