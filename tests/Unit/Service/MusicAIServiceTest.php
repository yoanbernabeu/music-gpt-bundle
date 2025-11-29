<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use YoanBernabeu\MusicGptBundle\DTO\MusicAI\MusicAIRequest;
use YoanBernabeu\MusicGptBundle\DTO\MusicAI\MusicAIResponse;
use YoanBernabeu\MusicGptBundle\Exception\AuthenticationException;
use YoanBernabeu\MusicGptBundle\Exception\RateLimitException;
use YoanBernabeu\MusicGptBundle\Service\HttpClient;
use YoanBernabeu\MusicGptBundle\Service\MusicAIService;

/**
 * @covers \YoanBernabeu\MusicGptBundle\Service\MusicAIService
 */
class MusicAIServiceTest extends TestCase
{
    private const BASE_URL = 'https://api.example.com';
    private const API_KEY = 'test-api-key';

    public function testGenerateReturnsMusicAIResponse(): void
    {
        $expectedData = [
            'success' => true,
            'message' => 'Message published to queue',
            'task_id' => 'task-123',
            'conversion_id_1' => 'conv-1',
            'conversion_id_2' => 'conv-2',
            'eta' => 100,
        ];

        $mockResponse = new MockResponse(
            json_encode($expectedData, JSON_THROW_ON_ERROR),
            ['http_code' => 200]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new MusicAIService($client);

        $request = new MusicAIRequest(prompt: 'Epic cinematic music');
        $response = $service->generate($request);

        $this->assertInstanceOf(MusicAIResponse::class, $response);
        $this->assertTrue($response->isSuccess());
        $this->assertSame('task-123', $response->getTaskId());
        $this->assertSame(100, $response->getEta());
    }

    public function testGenerateThrowsAuthenticationException(): void
    {
        $mockResponse = new MockResponse(
            '{"error": "Invalid API key"}',
            ['http_code' => 401]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new MusicAIService($client);

        $this->expectException(AuthenticationException::class);

        $request = new MusicAIRequest(prompt: 'Test');
        $service->generate($request);
    }

    public function testGenerateThrowsRateLimitException(): void
    {
        $mockResponse = new MockResponse(
            '{"error": "Rate limit exceeded", "retry_after": 60}',
            ['http_code' => 429]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new MusicAIService($client);

        $this->expectException(RateLimitException::class);

        $request = new MusicAIRequest(prompt: 'Test');
        $service->generate($request);
    }

    public function testGenerateWithAllParameters(): void
    {
        $expectedData = [
            'success' => true,
            'task_id' => 'task-456',
            'conversion_id_1' => 'conv-a',
            'conversion_id_2' => 'conv-b',
            'eta' => 200,
            'credit_estimate' => 2.5,
        ];

        $mockResponse = new MockResponse(
            json_encode($expectedData, JSON_THROW_ON_ERROR),
            ['http_code' => 200]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new MusicAIService($client);

        $request = new MusicAIRequest(
            prompt: 'Lofi hip hop beats',
            musicStyle: 'Lo-fi',
            lyrics: 'Verse 1...',
            makeInstrumental: false,
            vocalOnly: false,
            voiceId: 'drake',
            webhookUrl: 'https://example.com/webhook'
        );

        $response = $service->generate($request);

        $this->assertTrue($response->isSuccess());
        $this->assertSame('task-456', $response->getTaskId());
        $this->assertSame(2.5, $response->getCreditEstimate());
    }
}
