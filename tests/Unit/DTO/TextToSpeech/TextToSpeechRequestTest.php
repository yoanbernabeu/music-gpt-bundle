<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\Tests\Unit\DTO\TextToSpeech;

use PHPUnit\Framework\TestCase;
use YoanBernabeu\MusicGptBundle\DTO\TextToSpeech\TextToSpeechRequest;

/**
 * @covers \YoanBernabeu\MusicGptBundle\DTO\TextToSpeech\TextToSpeechRequest
 */
class TextToSpeechRequestTest extends TestCase
{
    public function testGettersReturnCorrectValues(): void
    {
        $request = new TextToSpeechRequest(
            text: 'Hello world, this is a test.',
            gender: 'male',
            voiceId: 'Drake',
            webhookUrl: 'https://example.com/webhook'
        );

        $this->assertSame('Hello world, this is a test.', $request->getText());
        $this->assertSame('male', $request->getGender());
        $this->assertSame('Drake', $request->getVoiceId());
        $this->assertNull($request->getSampleAudioUrl());
        $this->assertSame('https://example.com/webhook', $request->getWebhookUrl());
    }

    public function testGetEndpointReturnsCorrectValue(): void
    {
        $request = new TextToSpeechRequest(
            text: 'Test text',
            gender: 'female'
        );

        $this->assertSame('/TextToSpeech', $request->getEndpoint());
    }

    public function testGetMethodReturnsPost(): void
    {
        $request = new TextToSpeechRequest(
            text: 'Test text',
            gender: 'male'
        );

        $this->assertSame('POST', $request->getMethod());
    }

    public function testToArrayIncludesAllProvidedParameters(): void
    {
        $request = new TextToSpeechRequest(
            text: 'This is a sample text to convert to speech.',
            gender: 'female',
            voiceId: 'Adele',
            webhookUrl: 'https://example.com/webhook'
        );

        $expected = [
            'text' => 'This is a sample text to convert to speech.',
            'gender' => 'female',
            'voice_id' => 'Adele',
            'webhook_url' => 'https://example.com/webhook',
        ];

        $this->assertSame($expected, $request->toArray());
    }

    public function testToArrayExcludesNullParameters(): void
    {
        $request = new TextToSpeechRequest(
            text: 'Simple text',
            gender: 'male'
        );

        $array = $request->toArray();

        $this->assertArrayHasKey('text', $array);
        $this->assertArrayHasKey('gender', $array);
        $this->assertArrayNotHasKey('voice_id', $array);
        $this->assertArrayNotHasKey('sample_audio_url', $array);
        $this->assertArrayNotHasKey('webhook_url', $array);
    }

    public function testWithSampleAudioUrl(): void
    {
        $request = new TextToSpeechRequest(
            text: 'Test with sample audio',
            gender: 'male',
            sampleAudioUrl: 'https://example.com/voice-sample.mp3'
        );

        $this->assertNull($request->getVoiceId());
        $this->assertSame('https://example.com/voice-sample.mp3', $request->getSampleAudioUrl());

        $array = $request->toArray();
        $this->assertArrayHasKey('sample_audio_url', $array);
        $this->assertArrayNotHasKey('voice_id', $array);
    }

    public function testWithBothVoiceIdAndSampleAudioUrl(): void
    {
        $request = new TextToSpeechRequest(
            text: 'Test with both',
            gender: 'female',
            voiceId: 'Drake',
            sampleAudioUrl: 'https://example.com/sample.mp3'
        );

        $array = $request->toArray();
        
        // Both should be included if both are provided
        $this->assertArrayHasKey('voice_id', $array);
        $this->assertArrayHasKey('sample_audio_url', $array);
        $this->assertSame('Drake', $array['voice_id']);
        $this->assertSame('https://example.com/sample.mp3', $array['sample_audio_url']);
    }

    public function testWithLongText(): void
    {
        $longText = 'When I think of superheroes I think of super humans. I think of Superman, Wolverine and Wonder Woman. Usually they have a cape, or a mask to hide their face just in case.';
        
        $request = new TextToSpeechRequest(
            text: $longText,
            gender: 'male',
            voiceId: 'Drake'
        );

        $this->assertSame($longText, $request->getText());
    }
}

