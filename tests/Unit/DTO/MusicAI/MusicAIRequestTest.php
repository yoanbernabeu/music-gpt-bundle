<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\Tests\Unit\DTO\MusicAI;

use PHPUnit\Framework\TestCase;
use YoanBernabeu\MusicGptBundle\DTO\MusicAI\MusicAIRequest;

/**
 * @covers \YoanBernabeu\MusicGptBundle\DTO\MusicAI\MusicAIRequest
 */
class MusicAIRequestTest extends TestCase
{
    public function testCreateWithPromptOnly(): void
    {
        $request = new MusicAIRequest(prompt: 'A song about nature');

        $this->assertSame('A song about nature', $request->getPrompt());
        $this->assertNull($request->getMusicStyle());
        $this->assertNull($request->getLyrics());
        $this->assertFalse($request->isMakeInstrumental());
        $this->assertFalse($request->isVocalOnly());
        $this->assertNull($request->getVoiceId());
        $this->assertNull($request->getWebhookUrl());
    }

    public function testCreateWithAllParameters(): void
    {
        $request = new MusicAIRequest(
            prompt: 'A song about football',
            musicStyle: 'Pop',
            lyrics: 'We are the champions',
            makeInstrumental: true,
            vocalOnly: false,
            voiceId: 'Drake',
            webhookUrl: 'https://example.com/webhook'
        );

        $this->assertSame('A song about football', $request->getPrompt());
        $this->assertSame('Pop', $request->getMusicStyle());
        $this->assertSame('We are the champions', $request->getLyrics());
        $this->assertTrue($request->isMakeInstrumental());
        $this->assertFalse($request->isVocalOnly());
        $this->assertSame('Drake', $request->getVoiceId());
        $this->assertSame('https://example.com/webhook', $request->getWebhookUrl());
    }

    public function testToArrayWithMinimalData(): void
    {
        $request = new MusicAIRequest(prompt: 'Test prompt');

        $array = $request->toArray();

        $this->assertArrayHasKey('prompt', $array);
        $this->assertSame('Test prompt', $array['prompt']);
        $this->assertArrayNotHasKey('music_style', $array);
        $this->assertArrayNotHasKey('lyrics', $array);
        $this->assertArrayNotHasKey('webhook_url', $array);
    }

    public function testToArrayWithAllData(): void
    {
        $request = new MusicAIRequest(
            prompt: 'Test',
            musicStyle: 'Rock',
            lyrics: 'La la la',
            makeInstrumental: true,
            vocalOnly: true,
            voiceId: 'CustomVoice',
            webhookUrl: 'https://test.com'
        );

        $array = $request->toArray();

        $this->assertSame('Test', $array['prompt']);
        $this->assertSame('Rock', $array['music_style']);
        $this->assertSame('La la la', $array['lyrics']);
        $this->assertTrue($array['make_instrumental']);
        $this->assertTrue($array['vocal_only']);
        $this->assertSame('CustomVoice', $array['voice_id']);
        $this->assertSame('https://test.com', $array['webhook_url']);
    }

    public function testGetEndpoint(): void
    {
        $request = new MusicAIRequest(prompt: 'Test');

        $this->assertSame('/MusicAI', $request->getEndpoint());
    }

    public function testGetMethod(): void
    {
        $request = new MusicAIRequest(prompt: 'Test');

        $this->assertSame('POST', $request->getMethod());
    }
}
