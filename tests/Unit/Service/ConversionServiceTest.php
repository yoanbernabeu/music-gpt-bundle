<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use YoanBernabeu\MusicGptBundle\DTO\ConversionDetails;
use YoanBernabeu\MusicGptBundle\Enum\ConversionType;
use YoanBernabeu\MusicGptBundle\Exception\AuthenticationException;
use YoanBernabeu\MusicGptBundle\Exception\NotFoundException;
use YoanBernabeu\MusicGptBundle\Service\ConversionService;
use YoanBernabeu\MusicGptBundle\Service\HttpClient;

/**
 * @covers \YoanBernabeu\MusicGptBundle\Service\ConversionService
 */
class ConversionServiceTest extends TestCase
{
    private const BASE_URL = 'https://api.example.com';
    private const API_KEY = 'test-api-key';

    public function testGetByTaskIdReturnsConversionDetails(): void
    {
        $expectedData = [
            'conversion' => [
                'task_id' => 'task-123',
                'conversion_id' => 'conv-456',
                'status' => 'COMPLETED',
                'audio_url' => 'https://example.com/audio.mp3',
            ],
        ];

        $mockResponse = new MockResponse(
            json_encode($expectedData, JSON_THROW_ON_ERROR),
            ['http_code' => 200]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new ConversionService($client);

        $details = $service->getByTaskId('task-123', ConversionType::MUSIC_AI);

        $this->assertInstanceOf(ConversionDetails::class, $details);
        $this->assertSame('task-123', $details->getTaskId());
        $this->assertSame('conv-456', $details->getConversionId());
        $this->assertSame('COMPLETED', $details->getStatus());
        $this->assertTrue($details->isCompleted());
    }

    public function testGetByConversionIdReturnsConversionDetails(): void
    {
        $expectedData = [
            'conversion' => [
                'task_id' => 'task-789',
                'conversion_id' => 'conv-012',
                'status' => 'PROCESSING',
                'status_msg' => 'Your conversion is being processed',
            ],
        ];

        $mockResponse = new MockResponse(
            json_encode($expectedData, JSON_THROW_ON_ERROR),
            ['http_code' => 200]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new ConversionService($client);

        $details = $service->getByConversionId('conv-012', ConversionType::COVER);

        $this->assertInstanceOf(ConversionDetails::class, $details);
        $this->assertSame('conv-012', $details->getConversionId());
        $this->assertTrue($details->isProcessing());
        $this->assertSame('Your conversion is being processed', $details->getStatusMessage());
    }

    public function testGetByTaskIdWithMusicAIData(): void
    {
        $expectedData = [
            'conversion' => [
                'task_id' => 'task-123',
                'status' => 'COMPLETED',
                'conversion_path_1' => 'https://example.com/version1.mp3',
                'conversion_path_2' => 'https://example.com/version2.mp3',
                'conversion_path_wav_1' => 'https://example.com/version1.wav',
                'conversion_path_wav_2' => 'https://example.com/version2.wav',
                'title_1' => 'Generated Music - Version 1',
                'title_2' => 'Generated Music - Version 2',
                'music_style' => 'Lo-fi Hip Hop',
                'lyrics' => 'Test lyrics',
                'duration' => 180,
                'conversion_cost' => 2.5,
            ],
        ];

        $mockResponse = new MockResponse(
            json_encode($expectedData, JSON_THROW_ON_ERROR),
            ['http_code' => 200]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new ConversionService($client);

        $details = $service->getByTaskId('task-123', ConversionType::MUSIC_AI);

        $this->assertTrue($details->isCompleted());
        $this->assertSame('https://example.com/version1.mp3', $details->getAudioUrl1());
        $this->assertSame('https://example.com/version2.mp3', $details->getAudioUrl2());
        $this->assertSame('https://example.com/version1.wav', $details->getAudioWavUrl1());
        $this->assertSame('https://example.com/version2.wav', $details->getAudioWavUrl2());
        $this->assertSame('Generated Music - Version 1', $details->getTitle1());
        $this->assertSame('Generated Music - Version 2', $details->getTitle2());
        $this->assertSame('Lo-fi Hip Hop', $details->getMusicStyle());
        $this->assertSame('Test lyrics', $details->getLyrics());
        $this->assertSame(180, $details->getDuration());
        $this->assertSame(2.5, $details->getConversionCost());
    }

    public function testGetByTaskIdWithFailedStatus(): void
    {
        $expectedData = [
            'conversion' => [
                'task_id' => 'task-failed',
                'status' => 'FAILED',
                'status_msg' => 'Conversion failed due to invalid input',
            ],
        ];

        $mockResponse = new MockResponse(
            json_encode($expectedData, JSON_THROW_ON_ERROR),
            ['http_code' => 200]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new ConversionService($client);

        $details = $service->getByTaskId('task-failed', ConversionType::MUSIC_AI);

        $this->assertTrue($details->isFailed());
        $this->assertFalse($details->isCompleted());
        $this->assertFalse($details->isProcessing());
        $this->assertSame('Conversion failed due to invalid input', $details->getStatusMessage());
    }

    public function testGetByTaskIdThrowsAuthenticationException(): void
    {
        $mockResponse = new MockResponse(
            '{"error": "Invalid API key"}',
            ['http_code' => 401]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new ConversionService($client);

        $this->expectException(AuthenticationException::class);

        $service->getByTaskId('task-123', ConversionType::MUSIC_AI);
    }

    public function testGetByConversionIdThrowsNotFoundException(): void
    {
        $mockResponse = new MockResponse(
            '{"error": "Conversion not found"}',
            ['http_code' => 404]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new ConversionService($client);

        $this->expectException(NotFoundException::class);

        $service->getByConversionId('non-existent', ConversionType::COVER);
    }

    public function testGetByTaskIdWithCoverData(): void
    {
        $expectedData = [
            'conversion' => [
                'task_id' => 'task-cover',
                'conversion_id' => 'conv-cover',
                'status' => 'COMPLETED',
                'audio_url' => 'https://example.com/cover.mp3',
                'video_url' => 'https://example.com/cover.mp4',
                'image_url' => 'https://example.com/cover.jpg',
                'album_cover_thumbnail' => 'https://example.com/thumb.jpg',
                'title' => 'My Cover Song',
            ],
        ];

        $mockResponse = new MockResponse(
            json_encode($expectedData, JSON_THROW_ON_ERROR),
            ['http_code' => 200]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new ConversionService($client);

        $details = $service->getByTaskId('task-cover', ConversionType::COVER);

        $this->assertTrue($details->isCompleted());
        $this->assertSame('https://example.com/cover.mp3', $details->getAudioUrl());
        $this->assertSame('https://example.com/cover.mp4', $details->getVideoUrl());
        $this->assertSame('https://example.com/cover.jpg', $details->getImageUrl());
        $this->assertSame('https://example.com/thumb.jpg', $details->getThumbnailUrl());
        $this->assertSame('My Cover Song', $details->getTitle());
    }
}
