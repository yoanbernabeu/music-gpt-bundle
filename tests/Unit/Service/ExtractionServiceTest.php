<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use YoanBernabeu\MusicGptBundle\DTO\Extraction\ExtractionRequest;
use YoanBernabeu\MusicGptBundle\DTO\Extraction\ExtractionResponse;
use YoanBernabeu\MusicGptBundle\Exception\AuthenticationException;
use YoanBernabeu\MusicGptBundle\Exception\ValidationException;
use YoanBernabeu\MusicGptBundle\Service\ExtractionService;
use YoanBernabeu\MusicGptBundle\Service\HttpClient;

/**
 * @covers \YoanBernabeu\MusicGptBundle\Service\ExtractionService
 */
class ExtractionServiceTest extends TestCase
{
    private const BASE_URL = 'https://api.example.com';
    private const API_KEY = 'test-api-key';

    public function testExtractStemsReturnsExtractionResponse(): void
    {
        $expectedData = [
            'success' => true,
            'message' => 'Message published to queue',
            'task_id' => '62725d68-01e8-4c87-8fb0-298aa81c529c',
            'conversion_id' => '46b358c9-b22f-49d1-a68d-17901a6a549b',
            'eta' => 11,
            'credit_estimate' => 2.5,
            'status' => 'IN_QUEUE',
        ];

        $mockResponse = new MockResponse(
            json_encode($expectedData, JSON_THROW_ON_ERROR),
            ['http_code' => 200]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new ExtractionService($client);

        $request = new ExtractionRequest(
            audioUrl: 'https://example.com/song.mp3',
            stems: ['vocals', 'drums']
        );

        $response = $service->extractStems($request);

        $this->assertInstanceOf(ExtractionResponse::class, $response);
        $this->assertTrue($response->isSuccess());
        $this->assertSame('62725d68-01e8-4c87-8fb0-298aa81c529c', $response->getTaskId());
        $this->assertSame('46b358c9-b22f-49d1-a68d-17901a6a549b', $response->getConversionId());
        $this->assertSame(11, $response->getEta());
        $this->assertSame(2.5, $response->getCreditEstimate());
    }

    public function testExtractStemsWithAudioFile(): void
    {
        $expectedData = [
            'success' => true,
            'task_id' => 'task-789',
            'conversion_id' => 'conv-789',
            'eta' => 15,
        ];

        $mockResponse = new MockResponse(
            json_encode($expectedData, JSON_THROW_ON_ERROR),
            ['http_code' => 200]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new ExtractionService($client);

        $request = new ExtractionRequest(
            audioFile: '/path/to/audio.wav',
            stems: ['vocals', 'instrumental']
        );

        $response = $service->extractStems($request);

        $this->assertTrue($response->isSuccess());
        $this->assertSame('task-789', $response->getTaskId());
    }

    public function testExtractStemsWithAllParameters(): void
    {
        $expectedData = [
            'success' => true,
            'task_id' => 'task-full',
            'conversion_id' => 'conv-full',
            'eta' => 20,
            'credit_estimate' => 3.0,
        ];

        $mockResponse = new MockResponse(
            json_encode($expectedData, JSON_THROW_ON_ERROR),
            ['http_code' => 200]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new ExtractionService($client);

        $request = new ExtractionRequest(
            audioUrl: 'https://youtube.com/watch?v=abc123',
            stems: ['vocals', 'drums', 'bass', 'guitar'],
            preprocessingOptions: ['Denoise', 'Dereverb'],
            webhookUrl: 'https://example.com/webhook'
        );

        $response = $service->extractStems($request);

        $this->assertTrue($response->isSuccess());
        $this->assertSame('task-full', $response->getTaskId());
        $this->assertSame(3.0, $response->getCreditEstimate());
    }

    public function testExtractStemsWithPreprocessingOnly(): void
    {
        $expectedData = [
            'success' => true,
            'task_id' => 'task-preprocess',
            'conversion_id' => 'conv-preprocess',
            'eta' => 8,
        ];

        $mockResponse = new MockResponse(
            json_encode($expectedData, JSON_THROW_ON_ERROR),
            ['http_code' => 200]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new ExtractionService($client);

        $request = new ExtractionRequest(
            audioUrl: 'https://example.com/noisy-audio.mp3',
            preprocessingOptions: ['Denoise', 'Deecho', 'Dereverb']
        );

        $response = $service->extractStems($request);

        $this->assertTrue($response->isSuccess());
        $this->assertSame('task-preprocess', $response->getTaskId());
    }

    public function testExtractStemsThrowsAuthenticationException(): void
    {
        $mockResponse = new MockResponse(
            '{"error": "Invalid API key"}',
            ['http_code' => 401]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new ExtractionService($client);

        $this->expectException(AuthenticationException::class);

        $request = new ExtractionRequest(
            audioUrl: 'https://example.com/song.mp3',
            stems: ['vocals']
        );
        $service->extractStems($request);
    }

    public function testExtractStemsThrowsValidationException(): void
    {
        $mockResponse = new MockResponse(
            '{"error": "No audio provided", "errors": ["audio_url and audio_file cannot both be None"]}',
            ['http_code' => 422]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new ExtractionService($client);

        $this->expectException(ValidationException::class);

        $request = new ExtractionRequest();
        $service->extractStems($request);
    }

    public function testExtractStemsWithMultipleStems(): void
    {
        $expectedData = [
            'success' => true,
            'task_id' => 'task-multi',
            'conversion_id' => 'conv-multi',
            'eta' => 25,
            'credit_estimate' => 4.5,
        ];

        $mockResponse = new MockResponse(
            json_encode($expectedData, JSON_THROW_ON_ERROR),
            ['http_code' => 200]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new ExtractionService($client);

        $request = new ExtractionRequest(
            audioUrl: 'https://example.com/full-song.mp3',
            stems: [
                'vocals',
                'drums',
                'bass',
                'guitar',
                'piano',
                'strings',
            ]
        );

        $response = $service->extractStems($request);

        $this->assertTrue($response->isSuccess());
        $this->assertSame(4.5, $response->getCreditEstimate());
    }
}
