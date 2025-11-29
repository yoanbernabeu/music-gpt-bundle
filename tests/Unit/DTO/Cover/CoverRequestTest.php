<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\Tests\Unit\DTO\Cover;

use PHPUnit\Framework\TestCase;
use YoanBernabeu\MusicGptBundle\DTO\Cover\CoverRequest;

/**
 * @covers \YoanBernabeu\MusicGptBundle\DTO\Cover\CoverRequest
 */
class CoverRequestTest extends TestCase
{
    public function testGettersReturnCorrectValues(): void
    {
        $request = new CoverRequest(
            audioUrl: 'https://example.com/audio.mp3',
            voiceId: 'Drake',
            pitch: 2,
            webhookUrl: 'https://example.com/webhook'
        );

        $this->assertSame('https://example.com/audio.mp3', $request->getAudioUrl());
        $this->assertNull($request->getAudioFile());
        $this->assertSame('Drake', $request->getVoiceId());
        $this->assertSame(2, $request->getPitch());
        $this->assertSame('https://example.com/webhook', $request->getWebhookUrl());
    }

    public function testGetEndpointReturnsCorrectValue(): void
    {
        $request = new CoverRequest(
            audioUrl: 'https://example.com/audio.mp3',
            voiceId: 'Drake'
        );

        $this->assertSame('/Cover', $request->getEndpoint());
    }

    public function testGetMethodReturnsPost(): void
    {
        $request = new CoverRequest(
            audioUrl: 'https://example.com/audio.mp3',
            voiceId: 'Drake'
        );

        $this->assertSame('POST', $request->getMethod());
    }

    public function testToArrayIncludesAllProvidedParameters(): void
    {
        $request = new CoverRequest(
            audioUrl: 'https://example.com/audio.mp3',
            voiceId: 'Drake',
            pitch: 3,
            webhookUrl: 'https://example.com/webhook'
        );

        $expected = [
            'audio_url' => 'https://example.com/audio.mp3',
            'voice_id' => 'Drake',
            'pitch' => 3,
            'webhook_url' => 'https://example.com/webhook',
        ];

        $this->assertSame($expected, $request->toArray());
    }

    public function testToArrayExcludesNullParameters(): void
    {
        $request = new CoverRequest(
            audioUrl: 'https://example.com/audio.mp3',
            voiceId: 'Drake'
        );

        $array = $request->toArray();

        $this->assertArrayHasKey('audio_url', $array);
        $this->assertArrayHasKey('voice_id', $array);
        $this->assertArrayNotHasKey('audio_file', $array);
        $this->assertArrayNotHasKey('webhook_url', $array);
    }

    public function testToArrayExcludesZeroPitch(): void
    {
        $request = new CoverRequest(
            audioUrl: 'https://example.com/audio.mp3',
            voiceId: 'Drake',
            pitch: 0
        );

        $array = $request->toArray();

        $this->assertArrayNotHasKey('pitch', $array);
    }

    public function testToArrayIncludesNegativePitch(): void
    {
        $request = new CoverRequest(
            audioUrl: 'https://example.com/audio.mp3',
            voiceId: 'Drake',
            pitch: -5
        );

        $array = $request->toArray();

        $this->assertArrayHasKey('pitch', $array);
        $this->assertSame(-5, $array['pitch']);
    }

    public function testWithAudioFile(): void
    {
        $request = new CoverRequest(
            audioFile: '/path/to/audio.wav',
            voiceId: 'Drake'
        );

        $this->assertNull($request->getAudioUrl());
        $this->assertSame('/path/to/audio.wav', $request->getAudioFile());

        $array = $request->toArray();
        $this->assertArrayHasKey('audio_file', $array);
        $this->assertArrayNotHasKey('audio_url', $array);
    }
}
