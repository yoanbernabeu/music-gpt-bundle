<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use YoanBernabeu\MusicGptBundle\DTO\Voice\VoicesResponse;
use YoanBernabeu\MusicGptBundle\Exception\AuthenticationException;
use YoanBernabeu\MusicGptBundle\Service\HttpClient;
use YoanBernabeu\MusicGptBundle\Service\VoiceService;

/**
 * @covers \YoanBernabeu\MusicGptBundle\Service\VoiceService
 */
class VoiceServiceTest extends TestCase
{
    private const BASE_URL = 'https://api.example.com';
    private const API_KEY = 'test-api-key';

    public function testSearchVoicesReturnsVoicesResponse(): void
    {
        $expectedData = [
            'success' => true,
            'voices' => [
                ['voice_id' => 'JustinBieber', 'voice_name' => 'Justin Bieber'],
                ['voice_id' => 'Drake', 'voice_name' => 'Drake'],
            ],
            'limit' => 20,
            'page' => 0,
            'total' => 2,
        ];

        $mockResponse = new MockResponse(
            json_encode($expectedData, JSON_THROW_ON_ERROR),
            ['http_code' => 200]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new VoiceService($client);

        $response = $service->searchVoices('Bieber');

        $this->assertInstanceOf(VoicesResponse::class, $response);
        $this->assertTrue($response->isSuccess());
        $this->assertCount(2, $response->getVoices());
        $this->assertSame(20, $response->getLimit());
        $this->assertSame(0, $response->getPage());
        $this->assertSame(2, $response->getTotal());
    }

    public function testSearchVoicesWithCustomLimitAndPage(): void
    {
        $expectedData = [
            'success' => true,
            'voices' => [
                ['voice_id' => 'voice1', 'voice_name' => 'Voice 1'],
            ],
            'limit' => 50,
            'page' => 2,
            'total' => 150,
        ];

        $mockResponse = new MockResponse(
            json_encode($expectedData, JSON_THROW_ON_ERROR),
            ['http_code' => 200]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new VoiceService($client);

        $response = $service->searchVoices('test', 50, 2);

        $this->assertTrue($response->isSuccess());
        $this->assertSame(50, $response->getLimit());
        $this->assertSame(2, $response->getPage());
        $this->assertSame(150, $response->getTotal());
    }

    public function testSearchVoicesWithNoResults(): void
    {
        $expectedData = [
            'success' => true,
            'voices' => [],
            'limit' => 20,
            'page' => 0,
            'total' => 0,
        ];

        $mockResponse = new MockResponse(
            json_encode($expectedData, JSON_THROW_ON_ERROR),
            ['http_code' => 200]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new VoiceService($client);

        $response = $service->searchVoices('nonexistent');

        $this->assertTrue($response->isSuccess());
        $this->assertCount(0, $response->getVoices());
        $this->assertSame(0, $response->getTotal());
    }

    public function testGetAllVoicesReturnsVoicesResponse(): void
    {
        $expectedData = [
            'success' => true,
            'voices' => [
                ['voice_id' => '00126f62-1f31-434a-abc6-a5e958a737e3', 'voice_name' => 'Joji'],
                ['voice_id' => '0031cf05-6d3d-4c15-9115-d8236590b957', 'voice_name' => 'Amy Winehouse'],
            ],
            'limit' => 20,
            'page' => 0,
            'total' => 3108,
        ];

        $mockResponse = new MockResponse(
            json_encode($expectedData, JSON_THROW_ON_ERROR),
            ['http_code' => 200]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new VoiceService($client);

        $response = $service->getAllVoices();

        $this->assertInstanceOf(VoicesResponse::class, $response);
        $this->assertTrue($response->isSuccess());
        $this->assertCount(2, $response->getVoices());
        $this->assertSame(3108, $response->getTotal());
    }

    public function testGetAllVoicesWithPagination(): void
    {
        $expectedData = [
            'success' => true,
            'voices' => [
                ['voice_id' => 'voice1', 'voice_name' => 'Voice 1'],
                ['voice_id' => 'voice2', 'voice_name' => 'Voice 2'],
            ],
            'limit' => 100,
            'page' => 5,
            'total' => 3108,
        ];

        $mockResponse = new MockResponse(
            json_encode($expectedData, JSON_THROW_ON_ERROR),
            ['http_code' => 200]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new VoiceService($client);

        $response = $service->getAllVoices(100, 5);

        $this->assertTrue($response->isSuccess());
        $this->assertSame(100, $response->getLimit());
        $this->assertSame(5, $response->getPage());
        $this->assertSame(3108, $response->getTotal());
    }

    public function testSearchVoicesThrowsAuthenticationException(): void
    {
        $mockResponse = new MockResponse(
            '{"error": "Invalid API key"}',
            ['http_code' => 401]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new VoiceService($client);

        $this->expectException(AuthenticationException::class);

        $service->searchVoices('test');
    }

    public function testGetAllVoicesThrowsAuthenticationException(): void
    {
        $mockResponse = new MockResponse(
            '{"error": "Invalid API key"}',
            ['http_code' => 401]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new VoiceService($client);

        $this->expectException(AuthenticationException::class);

        $service->getAllVoices();
    }

    public function testSearchVoicesReturnsCorrectVoiceData(): void
    {
        $expectedData = [
            'success' => true,
            'voices' => [
                ['voice_id' => 'Drake', 'voice_name' => 'Drake'],
                ['voice_id' => 'Adele', 'voice_name' => 'Adele'],
                ['voice_id' => 'TaylorSwift', 'voice_name' => 'Taylor Swift'],
            ],
            'limit' => 20,
            'page' => 0,
            'total' => 3,
        ];

        $mockResponse = new MockResponse(
            json_encode($expectedData, JSON_THROW_ON_ERROR),
            ['http_code' => 200]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client = new HttpClient($httpClient, self::BASE_URL, self::API_KEY);
        $service = new VoiceService($client);

        $response = $service->searchVoices('popular');
        $voices = $response->getVoices();

        $this->assertCount(3, $voices);
        $this->assertSame('Drake', $voices[0]->getVoiceId());
        $this->assertSame('Drake', $voices[0]->getVoiceName());
        $this->assertSame('Taylor Swift', $voices[2]->getVoiceName());
    }
}

