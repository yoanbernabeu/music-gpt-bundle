<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\Tests\Unit\DTO\Extraction;

use PHPUnit\Framework\TestCase;
use YoanBernabeu\MusicGptBundle\DTO\Extraction\ExtractionRequest;

/**
 * @covers \YoanBernabeu\MusicGptBundle\DTO\Extraction\ExtractionRequest
 */
class ExtractionRequestTest extends TestCase
{
    public function testGettersReturnCorrectValues(): void
    {
        $request = new ExtractionRequest(
            audioUrl: 'https://example.com/audio.mp3',
            stems: ['vocals', 'drums'],
            preprocessingOptions: ['Denoise', 'Dereverb'],
            webhookUrl: 'https://example.com/webhook'
        );

        $this->assertSame('https://example.com/audio.mp3', $request->getAudioUrl());
        $this->assertNull($request->getAudioFile());
        $this->assertSame(['vocals', 'drums'], $request->getStems());
        $this->assertSame(['Denoise', 'Dereverb'], $request->getPreprocessingOptions());
        $this->assertSame('https://example.com/webhook', $request->getWebhookUrl());
    }

    public function testGetEndpointReturnsCorrectValue(): void
    {
        $request = new ExtractionRequest(
            audioUrl: 'https://example.com/audio.mp3'
        );

        $this->assertSame('/Extraction', $request->getEndpoint());
    }

    public function testGetMethodReturnsPost(): void
    {
        $request = new ExtractionRequest(
            audioUrl: 'https://example.com/audio.mp3'
        );

        $this->assertSame('POST', $request->getMethod());
    }

    public function testToArrayIncludesAllProvidedParameters(): void
    {
        $request = new ExtractionRequest(
            audioUrl: 'https://example.com/audio.mp3',
            stems: ['vocals', 'instrumental'],
            preprocessingOptions: ['Denoise'],
            webhookUrl: 'https://example.com/webhook'
        );

        $expected = [
            'audio_url' => 'https://example.com/audio.mp3',
            'stems' => ['vocals', 'instrumental'],
            'preprocessing_options' => ['Denoise'],
            'webhook_url' => 'https://example.com/webhook',
        ];

        $this->assertSame($expected, $request->toArray());
    }

    public function testToArrayExcludesNullAndEmptyParameters(): void
    {
        $request = new ExtractionRequest(
            audioUrl: 'https://example.com/audio.mp3'
        );

        $array = $request->toArray();

        $this->assertArrayHasKey('audio_url', $array);
        $this->assertArrayNotHasKey('audio_file', $array);
        $this->assertArrayNotHasKey('stems', $array);
        $this->assertArrayNotHasKey('preprocessing_options', $array);
        $this->assertArrayNotHasKey('webhook_url', $array);
    }

    public function testWithAudioFile(): void
    {
        $request = new ExtractionRequest(
            audioFile: '/path/to/audio.wav',
            stems: ['vocals']
        );

        $this->assertNull($request->getAudioUrl());
        $this->assertSame('/path/to/audio.wav', $request->getAudioFile());

        $array = $request->toArray();
        $this->assertArrayHasKey('audio_file', $array);
        $this->assertArrayNotHasKey('audio_url', $array);
    }

    public function testWithMultipleStems(): void
    {
        $stems = [
            'vocals',
            'drums',
            'bass',
            'guitar',
            'piano',
        ];

        $request = new ExtractionRequest(
            audioUrl: 'https://example.com/song.mp3',
            stems: $stems
        );

        $this->assertSame($stems, $request->getStems());
        $array = $request->toArray();
        $this->assertArrayHasKey('stems', $array);
        $this->assertSame($stems, $array['stems']);
    }

    public function testWithAllPreprocessingOptions(): void
    {
        $request = new ExtractionRequest(
            audioUrl: 'https://example.com/song.mp3',
            preprocessingOptions: ['Denoise', 'Deecho', 'Dereverb']
        );

        $this->assertSame(['Denoise', 'Deecho', 'Dereverb'], $request->getPreprocessingOptions());
    }

    public function testWithEmptyStems(): void
    {
        $request = new ExtractionRequest(
            audioUrl: 'https://example.com/song.mp3',
            stems: []
        );

        $array = $request->toArray();
        $this->assertArrayNotHasKey('stems', $array);
    }

    public function testWithSpecificStems(): void
    {
        $request = new ExtractionRequest(
            audioUrl: 'https://youtube.com/watch?v=abc123',
            stems: ['male_vocal', 'female_vocal', 'kick_drum', 'snare_drum']
        );

        $array = $request->toArray();
        $this->assertArrayHasKey('stems', $array);
        $this->assertCount(4, $array['stems']);
        $this->assertContains('male_vocal', $array['stems']);
        $this->assertContains('snare_drum', $array['stems']);
    }
}

