<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use YoanBernabeu\MusicGptBundle\DTO\Cover\CoverRequest;
use YoanBernabeu\MusicGptBundle\DTO\Cover\CoverResponse;
use YoanBernabeu\MusicGptBundle\Exception\AuthenticationException;
use YoanBernabeu\MusicGptBundle\Exception\ValidationException;
use YoanBernabeu\MusicGptBundle\Service\CoverService;
use YoanBernabeu\MusicGptBundle\Service\HttpClient;

/**
 * @covers \YoanBernabeu\MusicGptBundle\Service\CoverService
 */
class CoverServiceTest extends TestCase
{
    private const BASE_URL = 'https://api.example.com';
    private const API_KEY = 'test-api-key';

    public function testCreateCoverReturnsCoverResponse(): void
    {
        $expectedData = [
            'success' => true,
            'message' => 'Message published to queue',
            'task_id' => 'task-cover-123',
            'conversion_id' => 'conv-cover-456',
            'eta' => 33,
            'credit_estimate' => 1.5,
            'status' => 'IN_QUEUE',
        ];

        $mockResponse = new MockResponse(
            json_encode($expectedData, JSON_THROW_ON_ERROR),
            ['http_code' => 200]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new CoverService($client);

        $request = new CoverRequest(
            audioUrl: 'https://example.com/song.mp3',
            voiceId: 'Drake'
        );

        $response = $service->createCover($request);

        $this->assertInstanceOf(CoverResponse::class, $response);
        $this->assertTrue($response->isSuccess());
        $this->assertSame('task-cover-123', $response->getTaskId());
        $this->assertSame('conv-cover-456', $response->getConversionId());
        $this->assertSame(33, $response->getEta());
        $this->assertSame(1.5, $response->getCreditEstimate());
    }

    public function testCreateCoverWithAudioFile(): void
    {
        $expectedData = [
            'success' => true,
            'task_id' => 'task-789',
            'conversion_id' => 'conv-789',
            'eta' => 45,
        ];

        $mockResponse = new MockResponse(
            json_encode($expectedData, JSON_THROW_ON_ERROR),
            ['http_code' => 200]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new CoverService($client);

        $request = new CoverRequest(
            audioFile: '/path/to/audio.wav',
            voiceId: 'Taylor Swift',
            pitch: 2
        );

        $response = $service->createCover($request);

        $this->assertTrue($response->isSuccess());
        $this->assertSame('task-789', $response->getTaskId());
    }

    public function testCreateCoverWithAllParameters(): void
    {
        $expectedData = [
            'success' => true,
            'task_id' => 'task-full',
            'conversion_id' => 'conv-full',
            'eta' => 60,
            'credit_estimate' => 2.0,
        ];

        $mockResponse = new MockResponse(
            json_encode($expectedData, JSON_THROW_ON_ERROR),
            ['http_code' => 200]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new CoverService($client);

        $request = new CoverRequest(
            audioUrl: 'https://youtube.com/watch?v=abc123',
            voiceId: 'Adele',
            pitch: -3,
            webhookUrl: 'https://example.com/webhook'
        );

        $response = $service->createCover($request);

        $this->assertTrue($response->isSuccess());
        $this->assertSame('task-full', $response->getTaskId());
        $this->assertSame(2.0, $response->getCreditEstimate());
    }

    public function testCreateCoverThrowsAuthenticationException(): void
    {
        $mockResponse = new MockResponse(
            '{"error": "Invalid API key"}',
            ['http_code' => 401]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new CoverService($client);

        $this->expectException(AuthenticationException::class);

        $request = new CoverRequest(
            audioUrl: 'https://example.com/song.mp3',
            voiceId: 'Drake'
        );
        $service->createCover($request);
    }

    public function testCreateCoverThrowsValidationException(): void
    {
        $mockResponse = new MockResponse(
            '{"error": "No audio provided", "errors": ["audio_url and audio_file cannot both be None"]}',
            ['http_code' => 422]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new CoverService($client);

        $this->expectException(ValidationException::class);

        $request = new CoverRequest(voiceId: 'Drake');
        $service->createCover($request);
    }
}

