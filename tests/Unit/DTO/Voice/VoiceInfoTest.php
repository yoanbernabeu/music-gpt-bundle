<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\Tests\Unit\DTO\Voice;

use PHPUnit\Framework\TestCase;
use YoanBernabeu\MusicGptBundle\DTO\Voice\VoiceInfo;

/**
 * @covers \YoanBernabeu\MusicGptBundle\DTO\Voice\VoiceInfo
 */
class VoiceInfoTest extends TestCase
{
    public function testGettersReturnCorrectValues(): void
    {
        $voice = new VoiceInfo(
            voiceId: 'JustinBieber',
            voiceName: 'Justin Bieber'
        );

        $this->assertSame('JustinBieber', $voice->getVoiceId());
        $this->assertSame('Justin Bieber', $voice->getVoiceName());
    }

    public function testFromArrayCreatesCorrectInstance(): void
    {
        $data = [
            'voice_id' => '00126f62-1f31-434a-abc6-a5e958a737e3',
            'voice_name' => 'Joji',
        ];

        $voice = VoiceInfo::fromArray($data);

        $this->assertSame('00126f62-1f31-434a-abc6-a5e958a737e3', $voice->getVoiceId());
        $this->assertSame('Joji', $voice->getVoiceName());
    }

    public function testFromArrayHandlesMissingFields(): void
    {
        $data = [];

        $voice = VoiceInfo::fromArray($data);

        $this->assertSame('', $voice->getVoiceId());
        $this->assertSame('', $voice->getVoiceName());
    }

    public function testFromArrayWithDifferentArtists(): void
    {
        $voices = [
            ['voice_id' => 'Drake', 'voice_name' => 'Drake'],
            ['voice_id' => 'Adele', 'voice_name' => 'Adele'],
            ['voice_id' => 'TaylorSwift', 'voice_name' => 'Taylor Swift'],
        ];

        foreach ($voices as $voiceData) {
            $voice = VoiceInfo::fromArray($voiceData);
            $this->assertSame($voiceData['voice_id'], $voice->getVoiceId());
            $this->assertSame($voiceData['voice_name'], $voice->getVoiceName());
        }
    }
}
