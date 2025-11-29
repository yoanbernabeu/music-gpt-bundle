<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use YoanBernabeu\MusicGptBundle\DTO\TextToSpeech\TextToSpeechRequest;
use YoanBernabeu\MusicGptBundle\DTO\TextToSpeech\TextToSpeechResponse;
use YoanBernabeu\MusicGptBundle\Exception\AuthenticationException;
use YoanBernabeu\MusicGptBundle\Exception\ValidationException;
use YoanBernabeu\MusicGptBundle\Service\HttpClient;
use YoanBernabeu\MusicGptBundle\Service\TextToSpeechService;

/**
 * @covers \YoanBernabeu\MusicGptBundle\Service\TextToSpeechService
 */
class TextToSpeechServiceTest extends TestCase
{
    private const BASE_URL = 'https://api.example.com';
    private const API_KEY = 'test-api-key';

    public function testCreateTextToSpeechReturnsTextToSpeechResponse(): void
    {
        $expectedData = [
            'success' => true,
            'message' => 'Message published to queue',
            'task_id' => '0a65cbb6-2ab8-4949-9ee0-0e8c138ac2cf',
            'conversion_id' => '6542baa6-d61f-4d90-b832-ed929d9c0996',
            'eta' => 17,
            'credit_estimate' => 0.68,
            'status' => 'IN_QUEUE',
        ];

        $mockResponse = new MockResponse(
            json_encode($expectedData, JSON_THROW_ON_ERROR),
            ['http_code' => 200]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new TextToSpeechService($client);

        $request = new TextToSpeechRequest(
            text: 'Hello, this is a test of the text to speech feature.',
            gender: 'male',
            voiceId: 'Drake'
        );

        $response = $service->createTextToSpeech($request);

        $this->assertInstanceOf(TextToSpeechResponse::class, $response);
        $this->assertTrue($response->isSuccess());
        $this->assertSame('0a65cbb6-2ab8-4949-9ee0-0e8c138ac2cf', $response->getTaskId());
        $this->assertSame('6542baa6-d61f-4d90-b832-ed929d9c0996', $response->getConversionId());
        $this->assertSame(17, $response->getEta());
        $this->assertSame(0.68, $response->getCreditEstimate());
    }

    public function testCreateTextToSpeechWithSampleAudioUrl(): void
    {
        $expectedData = [
            'success' => true,
            'task_id' => '72eed5b0-8652-4bb4-9a95-eb0ad4850f12',
            'conversion_id' => '648a6823-b2a4-47b3-801e-f452c567ae6f',
            'eta' => 19,
        ];

        $mockResponse = new MockResponse(
            json_encode($expectedData, JSON_THROW_ON_ERROR),
            ['http_code' => 200]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new TextToSpeechService($client);

        $request = new TextToSpeechRequest(
            text: 'Sample text with audio URL',
            gender: 'female',
            sampleAudioUrl: 'https://example.com/voice-sample.mp3'
        );

        $response = $service->createTextToSpeech($request);

        $this->assertTrue($response->isSuccess());
        $this->assertSame('72eed5b0-8652-4bb4-9a95-eb0ad4850f12', $response->getTaskId());
    }

    public function testCreateTextToSpeechWithAllParameters(): void
    {
        $expectedData = [
            'success' => true,
            'task_id' => 'task-full',
            'conversion_id' => 'conv-full',
            'eta' => 25,
            'credit_estimate' => 1.2,
        ];

        $mockResponse = new MockResponse(
            json_encode($expectedData, JSON_THROW_ON_ERROR),
            ['http_code' => 200]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new TextToSpeechService($client);

        $request = new TextToSpeechRequest(
            text: 'The character Sherlock Holmes first appeared in print in 1887\'s A Study in Scarlet.',
            gender: 'male',
            voiceId: 'Drake',
            webhookUrl: 'https://example.com/webhook'
        );

        $response = $service->createTextToSpeech($request);

        $this->assertTrue($response->isSuccess());
        $this->assertSame('task-full', $response->getTaskId());
        $this->assertSame(1.2, $response->getCreditEstimate());
    }

    public function testCreateTextToSpeechThrowsAuthenticationException(): void
    {
        $mockResponse = new MockResponse(
            '{"error": "Invalid API key"}',
            ['http_code' => 401]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new TextToSpeechService($client);

        $this->expectException(AuthenticationException::class);

        $request = new TextToSpeechRequest(
            text: 'Test text',
            gender: 'male',
            voiceId: 'Drake'
        );
        $service->createTextToSpeech($request);
    }

    public function testCreateTextToSpeechThrowsValidationException(): void
    {
        $mockResponse = new MockResponse(
            '{"error": "Text is required", "errors": ["text field cannot be empty"]}',
            ['http_code' => 422]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new TextToSpeechService($client);

        $this->expectException(ValidationException::class);

        $request = new TextToSpeechRequest(
            text: '',
            gender: 'male'
        );
        $service->createTextToSpeech($request);
    }

    public function testCreateTextToSpeechWithLongText(): void
    {
        $longText = 'When I think of superheroes I think of super humans. I think of Superman, Wolverine and Wonder Woman. '.
                   'Usually they have a cape, or a mask to hide their face just in case. They have X-ray vision and super-human strength. '.
                   'Some can even breathe in outer space.';

        $expectedData = [
            'success' => true,
            'task_id' => 'task-long',
            'conversion_id' => 'conv-long',
            'eta' => 35,
            'credit_estimate' => 1.5,
        ];

        $mockResponse = new MockResponse(
            json_encode($expectedData, JSON_THROW_ON_ERROR),
            ['http_code' => 200]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new TextToSpeechService($client);

        $request = new TextToSpeechRequest(
            text: $longText,
            gender: 'female',
            voiceId: 'Adele'
        );

        $response = $service->createTextToSpeech($request);

        $this->assertTrue($response->isSuccess());
        $this->assertSame('task-long', $response->getTaskId());
        $this->assertSame(1.5, $response->getCreditEstimate());
    }
}
